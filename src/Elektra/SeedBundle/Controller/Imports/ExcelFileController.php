<?php

namespace Elektra\SeedBundle\Controller\Imports;

use Elektra\SeedBundle\Entity\Imports\File;
use Symfony\Component\Form\FormInterface;

abstract class ExcelFileController extends FileController
{

    /**
     * @var \PHPExcel
     */
    protected $excel;

    /**
     * @var array
     */
    protected $fieldDefinitions;

    /**
     * @var array
     */
    protected $fieldMap;

    /**
     * @param File          $file
     * @param FormInterface $form
     *
     * @return bool
     */
    protected function processUploadedFile(File $file, FormInterface $form)
    {

        // 1) create the reader and load the uploaded file
        $success = $this->loadFile($file);
        if (!$success) {
            $this->get('session')->getFlashBag()->add('info', 'false E1');

            return false;
        }

        // 2) load the definitions for the single columns (sub-class)
        $this->fieldDefinitions = array();
        $this->loadFieldDefinitions();

        // 3) load the header row
        $success = $this->loadHeader($file);
        if (!$success) {
            $this->get('session')->getFlashBag()->add('info', 'false E2');

            return false;
        }

        // 4) call the prepare method (initialise variables needed for processing, etc)
        $this->prepareProcessing();

        // 5) process the data rows
        $maxDataRow = $this->excel->getActiveSheet()->getHighestDataRow();
        for ($row = 2; $row <= $maxDataRow; $row++) { // starting with 2 as 1 is the header row
            // read the data and fill in an "identifier-indexed" array
            $rowData = array();
            foreach ($this->fieldMap as $identifier => $index) {
                $rowData[$identifier] = $this->excel->getActiveSheet()->getCellByColumnAndRow($index, $row)->getValue();
            }
            // do the actual processing (sub-class)
            try {
                $success = $this->processDataEntry($rowData);
                if (!$success) {
                    $this->get('session')->getFlashBag()->add('info', 'false E3');

                    return false;
                }
            } catch (ImportException $exception) {
                // URGENT show error form exception
                $this->get('session')->getFlashBag()->add('info', 'false E4');
                $this->get('session')->getFlashBag()->add('info', $exception->getMessage());

                return false;
            }
        }

        return true;
    }

    /**
     * @param File $file
     *
     * @return bool
     */
    private final function loadFile(File $file)
    {

        $filePath = $file->getUploadPath() . '/' . $file->getUploadFile();
        $reader   = \PHPExcel_IOFactory::createReaderForFile($filePath);

        if ($reader instanceof \PHPExcel_Reader_CSV) {
            // URGENT show error: unsupported format
            $this->get('session')->getFlashBag()->add('info', 'false E5');

            return false;
        }

        try {
            $this->excel = $reader->load($filePath);
        } catch (\PHPExcel_Reader_Exception $exception) {
            // URGENT show error message from exception
            $this->get('session')->getFlashBag()->add('info', 'false E6');

            return false;
        }

        return true;
    }

    /**
     * @param File $file
     *
     * @return bool
     */
    private final function loadHeader(File $file)
    {

        $this->fieldMap = array();
        $maxDataColumn  = $this->excel->getActiveSheet()->getHighestDataColumn();
        $maxDataColumn++;

        for ($col = 'A', $i = 0; $col != $maxDataColumn; $col++, $i++) {
            $xlsIndex = $col . '1';
            $cell     = $this->excel->getActiveSheet()->getCell($xlsIndex);
            if ($cell->getValue() == '') {
                // URGENT show error -> empty header not allowed
                $this->get('session')->getFlashBag()->add('info', 'false E7');

                return false;
            }
            $this->loadHeaderField($i, $cell->getValue());
        }

        return true;
    }

    /**
     * @param $index
     * @param $value
     */
    private function loadHeaderField($index, $value)
    {

        $value = trim(strtolower($value));

        // find the identifier for this field
        $valueId = null;
        foreach ($this->fieldDefinitions as $identifier => $aliases) {
            if ($value == $identifier) {
                $valueId = $identifier;
                break;
            }
            foreach ($aliases as $alias) {
                if ($value == $alias) {
                    $valueId = $identifier;
                    break 2;
                }
            }
        }

        $this->fieldMap[$valueId] = $index;
    }

    /**
     * @param       $identifier
     * @param array $aliases
     */
    protected final function addFieldDefinition($identifier, array $aliases = array())
    {

        $identifier = trim($identifier);
        $aliases[]  = $identifier;
        $aliases    = array_unique($aliases);
        sort($aliases);

        $this->fieldDefinitions[$identifier] = $aliases;
    }

    /**
     * @param      $dateTime
     * @param      $date
     * @param      $time
     * @param      $timeZone
     * @param bool $nowIfInvalid
     *
     * @return int
     * @throws \Exception
     */
    protected final function getTimestamp($dateTime, $date, $time, $timeZone, $nowIfInvalid = true)
    {

        $dateString = '';
        $timeString = '';

        if ($dateTime !== null) {
            // datetime contains the full date + time
            $dateTime   = \PHPExcel_Shared_Date::ExcelToPHPObject($dateTime);
            $dateString = $dateTime->format('Y') . '-' . $dateTime->format('m') . '-' . $dateTime->format('d');
            $timeString = $dateTime->format('H') . ':' . $dateTime->format('i') . ':' . $dateTime->format('s');
        } else {
            if ($date !== null && $time !== null) {
                // date contains the date part and time the time part
                $date       = \PHPExcel_Shared_Date::ExcelToPHPObject($date);
                $time       = \PHPExcel_Shared_Date::ExcelToPHPObject($time);
                $dateString = $date->format('Y') . '-' . $date->format('m') . '-' . $date->format('d');
                $timeString = $time->format('H') . ':' . $time->format('i') . ':' . $time->format('s');
            }
        }

        // prepare the string for the time zone
        if ($timeZone === null) {
            $timeZoneString = '+00:00';
        } else {
            if ($timeZone >= 0) {
                $timeZoneString = '+' . str_pad($timeZone, 2, '0', STR_PAD_LEFT);
            } else {
                $timeZoneString = '-' . str_pad(($timeZone * -1), 2, '0', STR_PAD_LEFT);
            }
            $timeZoneString .= ':00';
        }

        if ($dateString === '' || $timeString === '' || $timeZoneString === '') {
            if ($nowIfInvalid) {
                $timestamp = time();
            } else {
                throw new ImportException('Cannot create a timestamp from the given data');
            }
        } else {
            $dateTimeString = $dateString . ' ' . $timeString . ' ' . $timeZoneString;
            $timestamp      = strtotime($dateTimeString);
        }

        return $timestamp;
    }

    /**
     * @return void
     */
    protected abstract function loadFieldDefinitions();

    /**
     * @param array $data
     *
     * @return bool
     */
    protected abstract function processDataEntry(array $data);

    /**
     * @return void
     */
    protected abstract function prepareProcessing();
}