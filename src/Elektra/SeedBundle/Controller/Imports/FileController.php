<?php

namespace Elektra\SeedBundle\Controller\Imports;

use Elektra\CrudBundle\Controller\Controller;
use Elektra\SeedBundle\Entity\EntityInterface;
use Elektra\SeedBundle\Entity\Imports\File;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

abstract class FileController extends Controller
{

    /**
     * @return string
     */
    protected final function getUploadDirectory()
    {

        $directory = dirname($this->get('kernel')->getRootDir());
        $directory .= '/var/uploads/' . $this->getCrud()->getDefinition()->getName();

        return $directory;
    }

    /**
     * @param EntityInterface $entity
     * @param FormInterface   $form
     *
     * @return bool
     */
    public function beforeAddEntity(EntityInterface $entity, FormInterface $form = null)
    {

        if (!$entity instanceof File) {
            // URGENT show error message
            $this->get('session')->getFlashBag()->add('info', 'false F1');
            return false;
        }

        // 1) move the uploaded file to it's final destination
        $success = $this->moveFile($entity);
        if (!$success) {
            $entity->setProcessed(false);
            $this->get('session')->getFlashBag()->add('info', 'false F2');
            return false;
        }

        // 2) process the uploaded file (task of sub-class)
        $success = $this->processUploadedFile($entity, $form);
        if (!$success) {
            $entity->setProcessed(false);
            $this->get('session')->getFlashBag()->add('info', 'false F3');
            return false;
        }

//        exit();

        return true;
    }

    /**
     * @param File $entity
     *
     * @return bool
     */
    private final function moveFile(File $entity)
    {

        $uploadFile  = $entity->getFile();
        $directory   = $this->getUploadDirectory();
        $newFileName = $this->getCrud()->getDefinition()->getName() . '_' . time() . '.' . $uploadFile->getClientOriginalExtension();

        try {
            $uploadFile->move($directory, $newFileName);
        } catch (FileException $exception) {
            // URGENT show error message from exception
            $this->get('session')->getFlashBag()->add('info', 'false F4');
            return false;
        }

        // set the new values on the entity
        $entity->setOriginalFile($uploadFile->getClientOriginalName());
        $entity->setFileType($uploadFile->getClientOriginalExtension());
        $entity->setUploadPath($directory);
        $entity->setUploadFile($newFileName);

        return true;
    }

    /**
     * @param File          $file
     * @param FormInterface $form
     *
     * @return bool
     */
    protected abstract function processUploadedFile(File $file, FormInterface $form);
}
//
//abstract class FileController2 //extends Controller
//{
//
//    /**
//     * NOTE: rows are 1-relative, columns are 0-relative
//     *
//     * @var \PHPExcel
//     */
//    protected $file;
//
//    protected $fields = array();
//
//    protected $map = array();
//
//    protected function getUploadDirectory()
//    {
//
//        $directory = dirname($this->get('kernel')->getRootDir());
//        $directory .= '/var/uploads/' . $this->getCrud()->getDefinition()->getName();
//
//        return $directory;
//    }
//
//    public function beforeAddEntity(EntityInterface $entity, FormInterface $form = null)
//    {
//
//        if ($entity instanceof File) {
//            $return = $this->moveUploadedFile($entity);
//            if ($return === false) {
//                $entity->setProcessed(false);
//
//                return true;
//            }
//
//            $return = $this->loadFile($entity);
//            if ($return === false) {
//                $entity->setProcessed(false);
//
//                return true;
//            }
//
//            $return = $this->loadHeaderFields($entity);
//            if ($return === false) {
//                $entity->setProcessed(false);
//
//                return true;
//            }
//
//            $return = $this->processFile();
//            if ($return === false) {
//                $entity->setProcessed(false);
//
//                return true;
//            }
//        }
//        exit();
//
//        return true;
//    }
//
//    private function processFile()
//    {
//
//        $maxDataRow = $this->file->getActiveSheet()->getHighestDataRow();
//
//        // starting with 2 as 1 is the header row
//        for ($row = 2; $row <= $maxDataRow; $row++) {
//
//            $rowData = array();
//            foreach ($this->map as $identifier => $index) {
//                $rowData[$identifier] = $this->file->getActiveSheet()->getCellByColumnAndRow($index, $row)->getValue();
//            }
//            $this->processRow($rowData);
//        }
//
//        return true;
//    }
//
//    private function moveUploadedFile(File $fileEntity)
//    {
//
//        $file      = $fileEntity->getFile();
//        $uploadDir = $this->getUploadDirectory();
//        $fileName  = $this->getCrud()->getDefinition()->getName() . '_' . time() . '.' . $file->getClientOriginalExtension();
//
//        try {
//            $file->move($uploadDir, $fileName);
//        } catch (FileException $e) {
//            // URGENT add error message form exception
//            return false;
//        }
//
//        $fileEntity->setOriginalFile($file->getClientOriginalName());
//        $fileEntity->setUploadPath($uploadDir);
//        $fileEntity->setUploadFile($fileName);
//        $fileEntity->setFileType($file->getClientOriginalExtension());
//
//        return true;
//    }
//
//    private function loadFile(File $fileEntity)
//    {
//
//        $path = $fileEntity->getUploadPath() . '/' . $fileEntity->getUploadFile();
//
//        $reader = \PHPExcel_IOFactory::createReaderForFile($path);
//
//        if ($reader instanceof \PHPExcel_Reader_CSV) {
//            // URGENT add message -> unsupported format
//            return false;
//        }
//
//        try {
//            $this->file = $reader->load($path);
//        } catch (\PHPExcel_Reader_Exception $e) {
//            // URGENT add error message form exception
//            return false;
//        }
//
//        return true;
//    }
//
//    private function loadHeaderFields(File $fileEntity)
//    {
//
//        $this->initialiseFieldDefinitions();
//        $maxDataColumn = $this->file->getActiveSheet()->getHighestDataColumn();
//        $columnStop    = $maxDataColumn;
//        $columnStop++;
//
//        for ($col = 'A', $i = 0; $col != $columnStop; $col++, $i++) {
//            $xlsIndex = $col . '1';
//            $cell     = $this->file->getActiveSheet()->getCell($xlsIndex);
//            $this->initialiseHeaderField($i, $cell->getValue());
//        }
//    }
//
//    private function initialiseHeaderField($index, $value)
//    {
//
//        $value                  = trim(strtolower($value));
//        $identifier             = $this->getFieldIdentifier($value);
//        $this->map[$identifier] = $index;
//    }
//
//    private function getFieldIdentifier($alias)
//    {
//
//        foreach ($this->fields as $identifier => $aliases) {
//            foreach ($aliases as $checkAlias) {
//                if ($alias == $checkAlias) {
//
//                    return $identifier;
//                }
//            }
//        }
//    }
//
//    protected function addFieldDefinition($identifier, $aliases = array())
//    {
//
//        if (!in_array($identifier, $aliases)) {
//            $aliases[] = $identifier;
//        }
//        $this->fields[$identifier] = $aliases;
//    }
//
//    protected abstract function initialiseFieldDefinitions();
//
//    protected abstract function processRow(array $rowData);
//
//    protected function getTimestamp($dateTime, $date, $time, $timeZone)
//    {
//
//        $dateString = '';
//        $timeString = '';
//
//        if ($dateTime !== null) {
//            // datetime contains the full date + time
//            $dateTime   = \PHPExcel_Shared_Date::ExcelToPHPObject($dateTime);
//            $dateString = $dateTime->format('Y') . '-' . $dateTime->format('m') . '-' . $dateTime->format('d');
//            $timeString = $dateTime->format('H') . ':' . $dateTime->format('i') . ':' . $dateTime->format('s');
//        } else {
//            if ($date !== null && $time !== null) {
//                // date contains the date part and time the time part
//                $date       = \PHPExcel_Shared_Date::ExcelToPHPObject($date);
//                $time       = \PHPExcel_Shared_Date::ExcelToPHPObject($time);
//                $dateString = $date->format('Y') . '-' . $date->format('m') . '-' . $date->format('d');
//                $timeString = $time->format('H') . ':' . $time->format('i') . ':' . $time->format('s');
//            }
//        }
//
//        // prepare the string for the time zone
//        if ($timeZone === null) {
//            $timeZoneString = '+00:00';
//        } else {
//            if ($timeZone >= 0) {
//                $timeZoneString = '+' . str_pad($timeZone, 2, '0', STR_PAD_LEFT);
//            } else {
//                $timeZoneString = '-' . str_pad(($timeZone * -1), 2, '0', STR_PAD_LEFT);
//            }
//            $timeZoneString .= ':00';
//        }
//
//        if ($dateString === '' || $timeString === '' || $timeZoneString === '') {
//            $timestamp = time();
//        } else {
//            $dateTimeString = $dateString . ' ' . $timeString . ' ' . $timeZoneString;
//            $timestamp      = strtotime($dateTimeString);
//        }
//
//        return $timestamp;
//    }
//}
