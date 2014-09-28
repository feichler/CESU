<?php

namespace Elektra\SeedBundle\Import;

use Elektra\SeedBundle\Entity\AnnotableInterface;
use Elektra\SeedBundle\Entity\Imports\Import;
use Elektra\SeedBundle\Entity\Notes\Note;
use Elektra\SeedBundle\Import\Processor\Helper;
use Elektra\UserBundle\Entity\User;

abstract class Processor
{

    /**
     * @var Type
     */
    protected $type;

    /**
     * @var \PHPExcel
     */
    protected $excel;

    /**
     * @var Import
     */
    protected $importEntity;

    /**
     * @var array
     */
    protected $cache;

    /**
     * @var Processor\Helper
     */
    protected $helper;

    /**
     * @param Type $type
     */
    public function __construct(Type $type)
    {

        $this->type = $type;

        $this->cache  = array();
        $this->helper = new Helper($type);
    }

    /**
     * @return \Elektra\SeedBundle\Import\Type
     */
    public function getType()
    {

        return $this->type;
    }

    /**
     * @param \PHPExcel $excel
     */
    public function setExcel($excel)
    {

        $this->excel = $excel;
    }

    /**
     * @return \PHPExcel
     */
    public function getExcel()
    {

        return $this->excel;
    }

    /**
     * @param \Elektra\SeedBundle\Entity\Imports\Import $importEntity
     */
    public function setImportEntity($importEntity)
    {

        $this->importEntity = $importEntity;
    }

    /**
     * @return \Elektra\SeedBundle\Entity\Imports\Import
     */
    public function getImportEntity()
    {

        return $this->importEntity;
    }

    /**
     * @return User
     */
    protected function getUser()
    {

        return $this->getType()->getCrud()->getService('security.context')->getToken()->getUser();
    }

    public function execute()
    {

        /*
         * Read the file header line and try to create the field mapping
         */
        try {
            $mapping = $this->createDataMapping();
        } catch (ImportException $exception) {
            $this->getType()->addErrorMessage($exception->getMessage());
            $this->getType()->addMessagesNow(0);

            return false;
        }

        /*
         * Mapping is now created and verified -> process the single data rows
         */
        $sheet   = $this->excel->setActiveSheetIndex(0);
        $maxRows = $sheet->getHighestDataRow();

        if ($maxRows < 2) {
            $this->getType()->addErrorMessage('No data rows found in file');
            $this->getType()->addMessagesNow(0);

            return false;
        }

        $error = false;
        for ($row = 2; $row <= $maxRows; $row++) { // starting with 2: rows are 1-relative and 1 is the header row
            echo '<br /><br />';
            $data = $this->parseDataRow($sheet, $row, $mapping);
            if ($data === null) {
                // empty row
                continue;
            } else if ($data === false) {
                // invalid data
                $error = true;
                continue;
            } else {
                if ($this->getType()->hasDateFields()) {
                    // prepare the date fields and add them to the data array
                    $data['timestamp'] = $this->getTimestamp($data);
                }

                // next, check if the given data is valid for inserting
                $rowDataValid = $this->checkAndPrepareRowData($data, $row);
                if (!$rowDataValid) {
//                    $this->getType()->addErrorMessage('Invalid data found at row ' . $row);
                    $error = true;
                }

                if ($error) {
                    // if an error already occurred, just continue to check the other rows, but do not process the data
                    $this->getType()->addMessagesNow($row);
                    continue;
                }

                $rowDataStored = $this->storeRowData($data, $row);

                if (!$rowDataStored) {
                    $error = true;
                } else {
                    $this->importEntity->incrementNumberOfEntries();
                }
                $this->getType()->addMessagesNow($row);
            }
        }

        if ($error) {
            // remove all success and info messages on error as they are invalid (no processing on error)
            $this->getType()->removeMessages('success');
            $this->getType()->removeMessages('info');

            return false;
        }

        return true;
    }

    /**
     * @param array $data
     * @param int   $row
     *
     * @return bool
     */
    protected abstract function checkAndPrepareRowData(array &$data, $row);

    /**
     * @param array $data
     * @param int   $row
     *
     * @return bool
     */
    protected abstract function storeRowData(array $data, $row);

    /**
     * @param \PHPExcel_Worksheet $sheet
     * @param int                 $row
     * @param array               $mapping
     *
     * @return array|bool|null
     */
    protected function parseDataRow(\PHPExcel_Worksheet $sheet, $row, array $mapping)
    {

        $fields         = $this->getType()->getFields();
        $data           = array();
        $cells          = array();
        $empty          = array();
        $error          = false;
        $dateTimeChecks = array();

        // parse the single cells
        foreach ($mapping as $id => $index) {
            $cells[$id] = $sheet->getCellByColumnAndRow($index, $row);
            $data[$id]  = trim($cells[$id]->getValue());
            if ($data[$id] == '') {
                $empty[$id] = true;
            } else {
                $empty[$id] = false;
            }

            // also do some minimal checks at this point
            if ($id == 'date' || $id == 'time') {
                $dateTimeChecks[$id] = \PHPExcel_Shared_Date::isDateTime($cells[$id]);
            } else if ($id == 'timezone') {
                $dateTimeChecks[$id] = false;
                if (is_numeric($data[$id]) && $data[$id] == (int) $data[$id]) {
                    if ($data[$id] <= 12 && $data[$id] >= -14) {
                        $dateTimeChecks[$id] = true;
                    }
                }
            }
        }

        // check for empty rows
        if (!in_array(false, $empty)) {
            $msg = 'Empty row at row index ' . $row . ' - skipping';
            $this->addNote('Skipping row', 'Row ' . $row . ' has no data');
            $this->getType()->addWarningMessage($msg);

            return null;
        }

        // check for the required fields (must be not empty)
        $missingFields = array();
        foreach ($fields as $field) {
            if ($field->required) {
                $requiredValue = $data[$field->id];
                if ($requiredValue == '') {
                    $missingFields[] = $field->name;
                    $error           = true;
                }
            }
        }

        if ($error) {
            if (count($missingFields) == 1) {
                $this->getType()->addErrorMessage('Required field is missing: "' . $missingFields[0] . '"');
            } else {
                $this->getType()->addErrorMessage('Required fields are missing: "' . implode('", "', $missingFields) . '"');
            }

            return false;
        }

        // process the dateTimeChecks
        if (in_array(false, $dateTimeChecks)) {
            foreach ($dateTimeChecks as $id => $check) {
                if ($check == false) {
                    $this->getType()->addErrorMessage('Invalid value at "' . $fields[$id]->name . '"');
                }
            }

            return false;
        }

        return $data;
    }

    /**
     * @return array
     * @throws ImportException
     */
    protected function createDataMapping()
    {

        $mapping = array();
        $error   = false;

        $sheet      = $this->excel->setActiveSheetIndex(0);
        $maxColumns = $sheet->getHighestDataColumn(1);
        $maxColumns++;

        for ($col = 'A', $i = 0; $col != $maxColumns; $col++, $i++) {
            $xlsIndex = $col . '1';
            $value    = trim($sheet->getCell($xlsIndex)->getValue());
            if ($value == '') {
                $this->getType()->addErrorMessage('Empty header cell at "' . $xlsIndex . '" found');
            }

            $found = false;
            foreach ($this->getType()->getFields() as $field) {
                if ($field->name == $value) {
                    $found               = true;
                    $mapping[$field->id] = $i;
                }
            }
            if (!$found) {
                $this->getType()->addErrorMessage('Unknown header found at "' . $xlsIndex . '": ' . $value);
                $error = true;
            }
        }

        // Mapping is created successfully -> check the required fields
        $missingFields = array();
        foreach ($this->getType()->getFields() as $field) {
            if ($field->required) {
                if (!array_key_exists($field->id, $mapping)) {
                    $missingFields[] = $field->name;
                    $error           = true;
                }
            }
        }

        if ($error) {
            if (count($missingFields) == 1) {
                $msg = 'Required field "' . $missingFields[0] . '" is missing in header row';
            } else {
                $msg = 'Required fields "' . implode('", "', $missingFields) . '" are missing in header row';
            }
            throw new ImportException($msg);
        }

        return $mapping;
    }

    /**
     * @param array $data
     *
     * @return int
     */
    protected function getTimestamp(array $data)
    {

        $date = $data['date'];
        $time = $data['time'];
        $tz   = $data['timezone'];

        if ($date == null) {
            $date = new \DateTime('not UTC');
        } else {
            $date = \PHPExcel_Shared_Date::ExcelToPHPObject($date);
        }
        $date = $date->format('Y-m-d');

        if ($time == null) {
            $time = new \DateTime('now UTC');
        } else {
            $time = \PHPExcel_Shared_Date::ExcelToPHPObject($time);
        }
        $time = $time->format('H:i:s');

        if ($tz == null) {
            $tz = '+00:00';
        } else {
            if ($tz >= 0) {
                $tz = '+' . str_pad($tz, 2, '0', STR_PAD_LEFT) . ':00';
            } else {
                $tz = '-' . str_pad(($tz * -1), 2, '0', STR_PAD_LEFT) . ':00';
            }
        }

        return strtotime($date . ' ' . $time . ' ' . $tz);
    }

    /**
     * @param string $title
     * @param string $message
     */
    public function addNote($title, $message)
    {

        $note = new Note();
        $note->setTitle($title);
        $note->setText($message);
        $note->setTimestamp(time());
        $note->setUser($this->getUser());

        $this->importEntity->getNotes()->add($note);
    }

    /**
     * @param AnnotableInterface $entity
     * @param int                $row
     */
    protected function addCreatedNote(AnnotableInterface $entity, $row)
    {

        $note = new Note();
        $note->setTitle('Created from import');
        $note->setText('Imported from file "' . $this->importEntity->getUploadFile()->getClientOriginalName() . '" - row ' . $row);
        $note->setTimestamp(time());
        $note->setUser($this->getUser());

        $entity->getNotes()->add($note);
    }
}