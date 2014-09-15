<?php

namespace Elektra\SeedBundle\Controller\Imports;

use Doctrine\ORM\EntityManager;
use Elektra\CrudBundle\Crud\Definition;
use Elektra\SeedBundle\Entity\Companies\WarehouseLocation;
use Elektra\SeedBundle\Entity\Events\EventType;
use Elektra\SeedBundle\Entity\Events\ShippingEvent;
use Elektra\SeedBundle\Entity\Events\UnitStatus;
use Elektra\SeedBundle\Entity\Imports\File;
use Elektra\SeedBundle\Entity\SeedUnits\Model;
use Elektra\SeedBundle\Entity\SeedUnits\PowerCordType;
use Elektra\SeedBundle\Entity\SeedUnits\SeedUnit;
use Symfony\Component\Security\Acl\Exception\Exception;

class SeedUnitController extends ExcelFileController
{

    /**
     * @var array
     */
    protected $storedEntites;

    /**
     * @return Definition
     */
    protected function getDefinition()
    {

        return $this->get('navigator')->getDefinition('Elektra', 'Seed', 'Imports', 'SeedUnit');
    }

    /**
     * @return void
     */
    protected function loadFieldDefinitions()
    {

        // serial number
        $this->addFieldDefinition('serial', array('serialnumber', 'serial number', 'serial#', 'serial #', 'serialno', 'serial no',));

        // model
        $this->addFieldDefinition('model', array('model number', 'modelnumber'));

        // power type
        $this->addFieldDefinition(
            'power',
            array(
                'country',
                'countrypowercord',
                'country powercord',
                'country power cord',
                'country (powercord)',
                'country (power cord)',
                'power',
                'power (country)',
                'powertype',
                'powertype (country)',
                'power type',
                'power type (country)',
                'powercordtype',
                'powercordtype (country)',
                'powercord type',
                'powercord type (country)',
                'power cord type',
                'power cord type (country)',
            )
        );

        // warehouse
        $this->addFieldDefinition('warehouse', array('location',));

        // timestamp (replacement for date + time)
        $this->addFieldDefinition('timestamp', array());

        // date
        $this->addFieldDefinition('date', array());

        // time
        $this->addFieldDefinition('time', array());

        // timezone
        $this->addFieldDefinition('timezone', array('gmt difference', 'gmt diff',));
    }

    protected function prepareProcessing()
    {

        $this->storedEntites = array(
            'model'     => array(),
            'power'     => array(),
            'warehouse' => array(),
        );
    }

    protected function processDataEntry(array $data)
    {

        // load the timestamp
        $timestamp = $this->loadTimeData($data);

        // load and check the other fields
        if (!array_key_exists('serial', $data)) {
            throw new ImportException('No serial number found in data');
        }
        $serial = $data['serial'];

        if (!array_key_exists('model', $data)) {
            throw new ImportException('No model name found in data');
        }
        $model = $data['model'];

        if (!array_key_exists('power', $data)) {
            throw new ImportException('No power type found in data');
        }
        $power = $data['power'];

        if (!array_key_exists('warehouse', $data)) {
            throw new ImportException('No warehouse found in data');
        }
        $warehouse = $data['warehouse'];

        // if this point is reached, every required field was found ... now check the values
        // serial: must not be found in the database as it needs to be unique
        // model: either the name is found in the database or a new one is created
        // power: same as model
        // warehouse: must be found in the database

        if ($this->serialExists($serial)) {
            throw new ImportException('Serial number "' . $serial . '" is already stored in the database');
        }

        $modelEntity     = $this->getModelEntity($model);
        $powerEntity     = $this->getPowerEntity($power);
        $warehouseEntity = $this->getWarehouseEntity($warehouse);

        // now everything is prepared -> store the new unit to the database
        $this->storeSeedUnit($serial, $modelEntity, $powerEntity, $warehouseEntity, $timestamp);

        return true;
    }

    /**
     * @param                   $serial
     * @param Model             $model
     * @param PowerCordType     $power
     * @param WarehouseLocation $warehouse
     * @param                   $timestamp
     */
    private function storeSeedUnit($serial, Model $model, PowerCordType $power, WarehouseLocation $warehouse, $timestamp)
    {

        // get the shipping event type
        $typeRepository = $this->getDoctrine()->getRepository($this->getCrud()->getDefinition('Elektra', 'Seed', 'Events', 'EventType')->getClassRepository());
        $typeEntity     = $typeRepository->findOneBy(array('internalName' => EventType::SHIPPING));

        // get the unit status
        $statusRepository = $this->getDoctrine()->getRepository($this->getCrud()->getDefinition('Elektra', 'Seed', 'Events', 'UnitStatus')->getClassRepository());
        $statusEntity = $statusRepository->findOneBy(array('internalName' => UnitStatus::AVAILABLE));

        // first create the required shipping event (initial arrival at warehouse)
        $event = new ShippingEvent();
        $event->setEventType($typeEntity);
        $event->setUnitStatus($statusEntity);
        $event->setLocation($warehouse);
        $event->setTimestamp($timestamp);
        $event->setComment('Seed Unit created');
        $event->setText('Seed Unit created');


        // create the seed unit itself
        $seedUnit = new SeedUnit();
        $seedUnit->setSerialNumber($serial);
        $seedUnit->setModel($model);
        $seedUnit->setPowerCordType($power);

        // link the event and unit together
        $event->setSeedUnit($seedUnit);
        $seedUnit->getEvents()->add($event);

        // persist the seed unit and event
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($event);
        $manager->persist($seedUnit);
    }

    /**
     * @param array $data
     *
     * @return int
     */
    private function loadTimeData(array $data)
    {

        $dateTime = null;
        $date     = null;
        $time     = null;
        $timeZone = null;
        if (array_key_exists('timestamp', $data)) {
            $dateTime = $data['timestamp'];
        }
        if (array_key_exists('date', $data)) {
            $date = $data['date'];
        }
        if (array_key_exists('time', $data)) {
            $time = $data['time'];
        }
        if (array_key_exists('timezone', $data)) {
            $timeZone = $data['timezone'];
        }
        $timestamp = $this->getTimestamp($dateTime, $date, $time, $timeZone, true);

        return $timestamp;
    }

    /**
     * @param $serial
     *
     * @return bool
     */
    private function serialExists($serial)
    {

        $unitRepository = $this->getDoctrine()->getRepository($this->getCrud()->getDefinition('Elektra', 'Seed', 'SeedUnits', 'SeedUnit')->getClassRepository());
        $unitEntity     = $unitRepository->findOneBy(array('serialNumber' => $serial));
        if ($unitEntity === null) {
            // serial not found -> can insert it
            return false;
        }

        return true;
    }

    /**
     * @param $model
     *
     * @return Model
     */
    private function getModelEntity($model)
    {

        $hash = md5(serialize($model));

        // check if already loaded / created
        if (!array_key_exists($hash, $this->storedEntites['model'])) {
            $manager = $this->getDoctrine()->getManager();

            // check the model (name, id)
            $modelRepository = $this->getDoctrine()->getRepository($this->getCrud()->getDefinition('Elektra', 'Seed', 'SeedUnits', 'Model')->getClassRepository());
            $modelEntity     = $modelRepository->findOneBy(array('name' => $model));
            if ($modelEntity === null) {
                // none found with the name -> check if $model is numeric and try by id
                if (is_int($model)) {
                    $modelEntity = $modelRepository->find($model);
                }
                if ($modelEntity === null) {
                    // if the entity is still not set (neither by name or id found), create a new one
                    $modelEntity = new Model();
                    $modelEntity->setName($model);
                    $manager->persist($modelEntity);
                }
            }
            $this->storedEntites['model'][$hash] = $modelEntity;
        }

        return $this->storedEntites['model'][$hash];
    }

    /**
     * @param $power
     *
     * @return PowerCordType
     */
    private function getPowerEntity($power)
    {

        $hash = md5(serialize($power));

        if (!array_key_exists($hash, $this->storedEntites['power'])) {
            $manager = $this->getDoctrine()->getManager();

            // check the power cord type (name, id)
            $powerRepository = $this->getDoctrine()->getRepository($this->getCrud()->getDefinition('Elektra', 'Seed', 'SeedUnits', 'PowerCordType')->getClassRepository());
            $powerEntity     = $powerRepository->findOneBy(array('name' => $power));
            if ($powerEntity === null) {
                // none found with the name -> check if $power is numeric and try by id
                if (is_int($power)) {
                    $powerEntity = $powerRepository->find($power);
                }
                if ($powerEntity === null) {
                    // if the entity is still not set (neither by name or id found), create a new one
                    $powerEntity = new PowerCordType();
                    $powerEntity->setName($power);
                    $manager->persist($powerEntity);
                }
            }

            $this->storedEntites['power'][$hash] = $powerEntity;
        }

        return $this->storedEntites['power'][$hash];
    }

    /**
     * @param $warehouse
     *
     * @return WarehouseLocation
     * @throws \Exception
     */
    private function getWarehouseEntity($warehouse)
    {

        $hash = md5(serialize($warehouse));

        if (!array_key_exists($hash, $this->storedEntites['warehouse'])) {

            // check the warehouse (locationIdentifier, shortName, id)
            $warehouseRepository = $this->getDoctrine()->getRepository($this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'WarehouseLocation')->getClassRepository());
            $warehouseEntity     = $warehouseRepository->findOneBy(array('locationIdentifier' => $warehouse));
            if ($warehouseEntity === null) {
                // not found with the location identifier -> try the shortName next
                $warehouseEntity = $warehouseRepository->findOneBy(array('shortName' => $warehouse));
                if ($warehouseEntity === null) {
                    // not found with the short name -> check if $power is warehouse and try by id
                    if (is_int($warehouse)) {
                        $warehouseEntity = $warehouseRepository->find($warehouse);
                    }
                    if ($warehouseEntity === null) {
                        // if the entity is still not set (neither be location identifier, name or id found) throw an exception
                        throw new ImportException('Cannot get a warehouse from the given data');
                    }
                }
            }
            $this->storedEntites['warehouse'][$hash] = $warehouseEntity;
        }

        return $this->storedEntites['warehouse'][$hash];
    }
}

//class SeedUnitController2 //extends FileController
//{
//
//    protected function initialiseFieldDefinitions()
//    {
//
//        // serial number
//        $this->addFieldDefinition(
//            'serial',
//            array(
//                'serialnumber',
//                'serial number',
//                'serial#',
//                'serial #',
//                'serialno',
//                'serial no',
//            )
//        );
//
//        // model
//        $this->addFieldDefinition(
//            'model',
//            array(
//                'model number',
//                'modelnumber'
//            )
//        );
//
//        // power type
//        $this->addFieldDefinition(
//            'power',
//            array(
//                'country',
//                'countrypowercord',
//                'country powercord',
//                'country power cord',
//                'country (powercord)',
//                'country (power cord)',
//                'power',
//                'power (country)',
//                'powertype',
//                'powertype (country)',
//                'power type',
//                'power type (country)',
//                'powercordtype',
//                'powercordtype (country)',
//                'powercord type',
//                'powercord type (country)',
//                'power cord type',
//                'power cord type (country)',
//            )
//        );
//
//        // warehouse
//        $this->addFieldDefinition(
//            'warehouse',
//            array(
//                'location',
//            )
//        );
//
//        // timestamp (replacement for date + time)
//        $this->addFieldDefinition(
//            'timestamp',
//            array()
//        );
//
//        // date
//        $this->addFieldDefinition(
//            'date',
//            array()
//        );
//
//        // time
//        $this->addFieldDefinition(
//            'time',
//            array()
//        );
//
//        // timezone
//        $this->addFieldDefinition(
//            'timezone',
//            array(
//                'gmt difference',
//                'gmt diff',
//            )
//        );
//    }
//
//    protected function processRow(array $rowData)
//    {
//
//        $manager = $this->getDoctrine()->getManager();
//
//        $dateTime = null;
//        $date     = null;
//        $time     = null;
//        $timeZone = null;
//        if (array_key_exists('timestamp', $rowData)) {
//            $dateTime = $rowData['timestamp'];
//        }
//        if (array_key_exists('date', $rowData)) {
//            $date = $rowData['date'];
//        }
//        if (array_key_exists('time', $rowData)) {
//            $time = $rowData['time'];
//        }
//        if (array_key_exists('timezone', $rowData)) {
//            $timeZone = $rowData['timezone'];
//        }
//        $timestamp = $this->getTimestamp($dateTime, $date, $time, $timeZone);
//
//        if (!array_key_exists('serial', $rowData)) {
//            // URGENT missing field error
//        }
//        $serial = $rowData['serial'];
//
//        if (!array_key_exists('model', $rowData)) {
//            // URGENT missing field error
//        }
//        $model = $rowData['model'];
//
//        if (!array_key_exists('power', $rowData)) {
//            // URGENT missing field error
//        }
//        $power = $rowData['power'];
//
//        if (!array_key_exists('warehouse', $rowData)) {
//            // URGENT missing field error
//        }
//        $warehouse = $rowData['warehouse'];
//
//        $seedUnit = new SeedUnit();
//        $seedUnit->setSerialNumber($serial);
//
//        if ($manager instanceof EntityManager) {
//            $modelRep = $this->getDoctrine()->getRepository($this->getCrud()->getDefinition('Elektra', 'Seed', 'SeedUnits', 'Model')->getClassEntity());
//            //            $modelRep->
//            $modelEntity = $modelRep->findOneBy(array('name' => $model));
//            if ($modelEntity === null) {
//                // TODO create new model entity
//                $modelEntity = new Model();
//                $modelEntity->setName($model);
//                $manager->persist($modelEntity);
//            } else {
//            }
//            $seedUnit->setModel($modelEntity);
//            //            //            var_dump($modelEntity);
//            //            echo $modelEntity->getId() . '<br />';
//        } else {
//            echo 'NOT!!!';
//        }
//
//        //        var_dump($dateTime);
//        //        echo ' - ';
//        //        var_dump($date);
//        //        echo ' - ';
//        //        var_dump($time);
//        //        echo ' - ';
//        //        var_dump($timeZone);
//        //        echo '<br />';
//        //
//        //        echo $timestamp . ' - '.date('Y-m-d H:i:s O P', $timestamp);
//        //        echo '<br /><br />';
//
//        //        $timestamp = 0;
//        //        $timeDiff  = 0; // UTC default
//        //        if (array_key_exists('timezone', $rowData)) {
//        //            $timeDiff = $rowData['timezone'] * 3600;
//        //        }
//        //        $zone        = timezone_name_from_abbr(null, $timeDiff, null);
//        //        $defaultZone = date_default_timezone_get();
//        //
//        //        //        date_default_timezone_set($zone);
//        //        // prepare the date
//        //        if (array_key_exists('timestamp', $rowData)) {
//        //        } else {
//        //            $date = null;
//        //
//        //            if (array_key_exists('date', $rowData)) {
//        //                $date      = \PHPExcel_Shared_Date::ExcelToPHPObject($rowData['date']);
//        //                $timeZone1 = new \DateTimeZone($zone);
//        //                echo $zone . '<br />';
//        //                $date->setTimezone($timeZone1);
//        //            }
//        //            if ($date instanceof \DateTime) {
//        //                echo 'DATE: ' . $date->format('Y-m-d H:i:s O P') . '<br />';
//        //            }
//        //            //            echo 'DATE: '. $date->.'<br />';
//        //
//        //            //            $date;
//        //            //            $time;
//        //            $time = null;
//        //            if (array_key_exists('time', $rowData)) {
//        //                $obj = \PHPExcel_Shared_Date::ExcelToPHPObject($rowData['time']);
//        //                $date->setTime($obj->format('H'), $obj->format('i'), $obj->format('s'));
//        //                //    echo $obj->format('Y').'-'.$obj->format('m').'-'.$obj->format('d').'<br />';
//        //                //    echo $obj->format('H').'-'.$obj->format('i').'-'.$obj->format('s').'<br />';
//        //                //    $hour = \PHPExcel_Style_NumberFormat::toFormattedString($rowData['time'], 'h');
//        //                //    $minute = \PHPExcel_Style_NumberFormat::toFormattedString($rowData['time'], 'ii');
//        //                //    $second = \PHPExcel_Style_NumberFormat::toFormattedString($rowData['time'], 'ss');
//        //                //    $minute = \PHPExcel_Shared_Date::ExcelToPHPObject($rowData['time'])->format('i');
//        //                //    echo $minute.'<br />';
//        //                //    $time = \PHPExcel_Style_NumberFormat::toFormattedString($rowData['time'], \PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME4);
//        //                //echo $hour.'-'.$minute.'-'.$second.'<br />';
//        //                //                        echo \PHPExcel_Style_NumberFormat::toFormattedString($rowData['time'], \PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME4).'<br />';
//        //            }
//        //
//        //            if ($date instanceof \DateTime) {
//        //                echo 'DATE: ' . $date->format('Y-m-d H:i:s O P') . '<br />';
//        //            }
//        //
//        //            $date->setTimezone(new \DateTimeZone('UTC'));
//        //            echo 'DATE: ' . $date->format('Y-m-d H:i:s O P') . '<br />';
//        //        }
//        //
//        //        if ($timestamp == 0) {
//        //            $timestamp == time();
//        //        }
//        //
//        //        //        $zone = timezone_name_from_abbr(null, $timeDiff, null);
//        //
//        //        echo $timeDiff . '<br />';
//        //        echo $zone . '<br />';
//        //
//        //        var_dump($rowData);
//        //        echo '<br /><br />';
//        //        date_default_timezone_set($defaultZone);
//    }
//
//    /**
//     * @return Definition
//     */
//    protected function getDefinition()
//    {
//
//        return $this->get('navigator')->getDefinition('Elektra', 'Seed', 'Imports', 'SeedUnit');
//    }
//
//    //    /**
//    //     * @param $file
//    //     *
//    //     * @return \PHPExcel_Reader_IReader
//    //     */
//    //    protected function getReader($file)
//    //    {
//    //
//    //        $reader = \PHPExcel_IOFactory::createReaderForFile($file);
//    //
//    //        return $reader;
//    //    }
//    //
//    //    protected function findCSVDelimiter($path) {
//    //
//    //        $testDelimiters = array(",", ";", "\t", "|");
//    //        $delimiterCount = array();
//    //        $delimiterRow = array();
//    //
//    //        foreach($testDelimiters as $delimiter) {
//    //            $handle = fopen($path,'r');
//    //            $csvLine = fgetcsv($handle, null, $delimiter);
//    //            $delimiterCount[$delimiter] = count($csvLine);
//    //            $delimiterRow[$delimiter] = $csvLine;
//    //        }
//    //
//    //        $highest = array_keys($delimiterCount, max($delimiterCount));
//    //        return array($highest[0], $delimiterRow[$highest[0]]);
//    //    }
//    //
//    //    /**
//    //     * @param \PHPExcel_Reader_IReader $reader
//    //     * @param                          $path
//    //     *
//    //     * @return \PHPExcel
//    //     */
//    //    protected function loadFile(\PHPExcel_Reader_IReader $reader, $path)
//    //    {
//    //
//    //        $return = null;
//    //
//    //        if ($reader instanceof \PHPExcel_Reader_CSV) {
//    //
//    //            // first find the right delimiter
//    //            list($delimiter, $firstRow) = $this->findCSVDelimiter($path);
//    //            var_dump($delimiter);
//    //            var_dump($firstRow);
//    //
//    //            $handle = fopen($path,'r');
//    //            echo '<br />';
//    //            while(($csvLine=fgetcsv($handle, null, $delimiter))) {
//    //                var_dump($csvLine);
//    //                echo '<br />';
//    //            }
//    ////                        $csvLine = fgetcsv($handle, null, $delimiter);
//    //
//    //
//    //
//    ////            // first find the right delimiter
//    ////            $testDelimiters = array(",", ";", "\t", "|");
//    ////            $testedDelimiters = array();
//    ////            foreach($testDelimiters as $delimiter) {
//    ////                $handle = fopen($path,'r');
//    //////                echo $delimiter.': ';
//    ////                $test = fgetcsv($handle, null, $delimiter);
//    //////                var_dump($test);
//    //////                echo '<br />';
//    ////                $testedDelimiters[$delimiter] = count($test);
//    //////                if(count($test) > 1) {
//    //////                    $foundDelimiter = $delimiter;
//    //////                    break;
//    //////                }
//    ////            }
//    ////
//    ////            var_dump($testedDelimiters);
//    ////            $a = array_keys($testedDelimiters, max($testedDelimiters));
//    ////            var_dump($a);
//    ////            echo 'got "'.$foundDelimiter.'"<br /';
//    //
//    ////
//    ////
//    ////            $testEncodings  = array(
//    ////                'UTF-8',
//    ////            );
//    ////
//    ////            //            [11]=>
//    ////            //  string(4) "7bit"
//    ////            //            [12]=>
//    ////            //  string(4) "8bit"
//    ////            //            [13]=>
//    ////            //  string(5) "UCS-4"
//    ////            //            [14]=>
//    ////            //  string(7) "UCS-4BE"
//    ////            //            [15]=>
//    ////            //  string(7) "UCS-4LE"
//    ////            //            [16]=>
//    ////            //  string(5) "UCS-2"
//    ////            //            [17]=>
//    ////            //  string(7) "UCS-2BE"
//    ////            //            [18]=>
//    ////            //  string(7) "UCS-2LE"
//    ////            //            [19]=>
//    ////            //  string(6) "UTF-32"
//    ////            //            [20]=>
//    ////            //  string(8) "UTF-32BE"
//    ////            //            [21]=>
//    ////            //  string(8) "UTF-32LE"
//    ////            //            [22]=>
//    ////            //  string(6) "UTF-16"
//    ////            //            [23]=>
//    ////            //  string(8) "UTF-16BE"
//    ////            //            [24]=>
//    ////            //  string(8) "UTF-16LE"
//    ////            //            [25]=>
//    ////            //  string(5) "UTF-8"
//    ////            //            [26]=>
//    ////            //  string(5) "UTF-7"
//    ////            //            [27]=>
//    ////            //  string(9) "UTF7-IMAP"
//    ////            //            [28]=>
//    ////            //  string(5) "ASCII"
//    ////            //            [29]=>
//    ////            //  string(6) "EUC-JP"
//    ////            //            [30]=>
//    ////            //  string(4) "SJIS"
//    ////            //            [31]=>
//    ////            //  string(9) "eucJP-win"
//    ////            //            [32]=>
//    ////            //  string(11) "EUC-JP-2004"
//    ////            //            [33]=>
//    ////            //  string(8) "SJIS-win"
//    ////            //            [34]=>
//    ////            //  string(18) "SJIS-Mobile#DOCOMO"
//    ////            //            [35]=>
//    ////            //  string(16) "SJIS-Mobile#KDDI"
//    ////            //            [36]=>
//    ////            //  string(20) "SJIS-Mobile#SOFTBANK"
//    ////            //            [37]=>
//    ////            //  string(8) "SJIS-mac"
//    ////            //            [38]=>
//    ////            //  string(9) "SJIS-2004"
//    ////            //            [39]=>
//    ////            //  string(19) "UTF-8-Mobile#DOCOMO"
//    ////            //            [40]=>
//    ////            //  string(19) "UTF-8-Mobile#KDDI-A"
//    ////            //            [41]=>
//    ////            //  string(19) "UTF-8-Mobile#KDDI-B"
//    ////            //            [42]=>
//    ////            //  string(21) "UTF-8-Mobile#SOFTBANK"
//    ////            //            [43]=>
//    ////            //  string(5) "CP932"
//    ////            //            [44]=>
//    ////            //  string(7) "CP51932"
//    ////            //            [45]=>
//    ////            //  string(3) "JIS"
//    ////            //            [46]=>
//    ////            //  string(11) "ISO-2022-JP"
//    ////            //            [47]=>
//    ////            //  string(14) "ISO-2022-JP-MS"
//    ////            //            [48]=>
//    ////            //  string(7) "GB18030"
//    ////            //            [49]=>
//    ////            //  string(12) "Windows-1252"
//    ////            //            [50]=>
//    ////            //  string(12) "Windows-1254"
//    ////            //            [51]=>
//    ////            //  string(10) "ISO-8859-1"
//    ////            //            [52]=>
//    ////            //  string(10) "ISO-8859-2"
//    ////            //            [53]=>
//    ////            //  string(10) "ISO-8859-3"
//    ////            //            [54]=>
//    ////            //  string(10) "ISO-8859-4"
//    ////            //            [55]=>
//    ////            //  string(10) "ISO-8859-5"
//    ////            //            [56]=>
//    ////            //  string(10) "ISO-8859-6"
//    ////            //            [57]=>
//    ////            //  string(10) "ISO-8859-7"
//    ////            //            [58]=>
//    ////            //  string(10) "ISO-8859-8"
//    ////            //            [59]=>
//    ////            //  string(10) "ISO-8859-9"
//    ////            //            [60]=>
//    ////            //  string(11) "ISO-8859-10"
//    ////            //            [61]=>
//    ////            //  string(11) "ISO-8859-13"
//    ////            //            [62]=>
//    ////            //  string(11) "ISO-8859-14"
//    ////            //            [63]=>
//    ////            //  string(11) "ISO-8859-15"
//    ////            //            [64]=>
//    ////            //  string(11) "ISO-8859-16"
//    ////            //            [65]=>
//    ////            //  string(6) "EUC-CN"
//    ////            //            [66]=>
//    ////            //  string(5) "CP936"
//    ////            //            [67]=>
//    ////            //  string(2) "HZ"
//    ////            //            [68]=>
//    ////            //  string(6) "EUC-TW"
//    ////            //            [69]=>
//    ////            //  string(5) "BIG-5"
//    ////            //            [70]=>
//    ////            //  string(5) "CP950"
//    ////            //            [71]=>
//    ////            //  string(6) "EUC-KR"
//    ////            //            [72]=>
//    ////            //  string(3) "UHC"
//    ////            //            [73]=>
//    ////            //  string(11) "ISO-2022-KR"
//    ////            //            [74]=>
//    ////            //  string(12) "Windows-1251"
//    ////            //            [75]=>
//    ////            //  string(5) "CP866"
//    ////            //            [76]=>
//    ////            //  string(6) "KOI8-R"
//    ////            //            [77]=>
//    ////            //  string(6) "KOI8-U"
//    ////            //            [78]=>
//    ////            //  string(9) "ArmSCII-8"
//    ////            //            [79]=>
//    ////            //  string(5) "CP850"
//    ////            //            [80]=>
//    ////            //  string(6) "JIS-ms"
//    ////            //            [81]=>
//    ////            //  string(16) "ISO-2022-JP-2004"
//    ////            //            [82]=>
//    ////            //  string(23) "ISO-2022-JP-MOBILE#KDDI"
//    ////            //            [83]=>
//    ////            //  string(7) "CP50220"
//    ////            //            [84]=>
//    ////            //  string(10) "CP50220raw"
//    ////            //            [85]=>
//    ////            //  string(7) "CP50221"
//    ////            //            [86]=>
//    ////            //  string(7) "CP50222"
//    ////
//    ////            // if csv file - need to check which delimiter is used
//    ////            $reader->setSheetIndex(0);
//    ////            $encodings = mb_list_encodings();
//    ////            //            echo '<pre>';
//    ////            //            var_dump($encodings);
//    ////            //            echo '</pre>';
//    ////            //            echo '<br />';
//    ////
//    ////            $delimiters = array(',', ';', "\t", '|');
//    ////
//    ////            foreach ($encodings as $encoding) {
//    ////
//    ////                echo 'testing encoding ' . $encoding . '<br />';
//    ////                //                if($encoding == 'pass' || $encoding == 'auto'|| $encoding == 'wchar'|| $encoding == 'byte2be') {
//    ////                //                    continue;
//    ////                //
//    ////                //                }
//    ////                //                if($encoding == 'byte2le'|| $encoding == 'byte4le'|| $encoding == 'byte4be'|| $encoding == 'BASE64'|| $encoding == 'UUENCODE') {
//    ////                //                    continue;
//    ////                //                }
//    ////                //                if($encoding == 'HTML-ENTITIES'|| $encoding == 'Quoted-Printable'|| $encoding == '7bit'|| $encoding == '8bit') {
//    ////                //                    continue;
//    ////                //                }
//    ////                //                if($encoding == 'UCS-4'|| $encoding == 'UCS-4BE'|| $encoding == 'UCS-4LE'|| $encoding == 'UCS-2'|| $encoding == 'UCS-2BE'|| $encoding == 'UCS-2LE'|| $encoding == 'UTF-32') {
//    ////                //                    continue;
//    ////                //                }
//    ////                $reader->setInputEncoding($encoding);
//    ////                foreach ($delimiters as $delimiter) {
//    ////                    $reader->setDelimiter($delimiter);
//    ////                    try {
//    ////                        $return = $reader->load($path);
//    ////                        //                    echo $encoding.'-'.$delimiter.':<br />';
//    ////                        $sheet = $return->getActiveSheet();
//    ////                        $a1    = $sheet->getCell('A1');
//    ////                        $b1    = $sheet->getCell('B1');
//    ////                        $c1    = $sheet->getCell('C1');
//    ////
//    ////                        echo 'A1: ' . $a1 . ' - B1: ' . $b1 . ' - C1: ' . $c1 . '<br />';
//    ////                        //                    var_dump($a1);
//    ////                        //                    echo $a1->getDataType().'<br />';
//    ////                        //                    echo $a1->getValue().'<br />';
//    ////                        if (is_string($a1->getValue())) {
//    ////
//    ////                            //                        echo 'TEST2: '. iconv('', 'ASCII/IGNORE', $a1->getValue()).'<br />';
//    ////
//    ////                            echo 'TESTING: ' . preg_replace('/[[:^print:]]/', '', $a1->getValue()) . '<br />';
//    ////                            $d = utf8_decode($a1->getValue());
//    ////                            $e = utf8_encode($a1->getValue());
//    ////                            echo 'D: ' . $d . ' <br />';
//    ////                            echo 'D2: ' . preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $d) . ' <br />';
//    ////                            echo 'E: ' . $e . ' <br />';
//    ////                            echo 'E2: ' . preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $e) . ' <br />';
//    ////                        }
//    ////
//    ////                        var_dump($a1->getValue());
//    ////                        echo '<br />';
//    ////                    } catch (\Exception $e) {
//    ////                        echo 'EXCEPTION!<br />';
//    ////                        //                    echo 'Exception at '.$encoding.' - '.$delimiter.'<br />';
//    ////                    }
//    ////                }
//    ////            }
//    //        } else {
//    //            $return = $reader->load($path);
//    //        }
//    //        echo get_class($return) . '<br />';
//    //
//    //        if ($return instanceof \PHPExcel) {
//    //            echo 'A1: ' . $return->getActiveSheet()->getCell('A1') . '<br />';
//    //        }
//    //
//    //        return $return;
//    //    }
//    //
//    //    protected function initialiseReader(\PHPExcel_Reader_IReader $reader)
//    //    {
//    //    }
//
//    protected function processUploadedFile(File $fileEntity, $path)
//    {
//
//        $reader = \PHPExcel_IOFactory::createReaderForFile($path);
//
//        if ($reader instanceof \PHPExcel_Reader_CSV) {
//            // URGENT add message -> unsupported format
//            return false;
//        }
//
//        echo get_class($reader) . '<br />';
//
//        $phpExcel = $reader->load($path);
//
//        if ($phpExcel instanceof \PHPExcel) {
//            $sheet      = $phpExcel->getActiveSheet();
//            $maxDataRow = $sheet->getHighestDataRow();
//            $maxDataCol = $sheet->getHighestDataColumn();
//            $colStop    = $maxDataCol;
//            $colStop++;
//
//            //            echo $maxDataRow . ' - ' . $maxDataCol . '<br />';
//            //            echo $colStop . '<br />';
//
//            $header = array();
//
//            for ($col = 'A', $i = 0; $col != $colStop; $col++, $i++) {
//                $index      = $col . '1';
//                $header[$i] = $sheet->getCell($index)->getValue();
//                echo $index . '<br />';
//                echo $i . '<br />';
//            }
//
//            var_dump($header);
//
//            //            getcolumn
//            //$sheet->getcolumn
//            //            echo '<br />';
//            //
//            //            $maxCol = $sheet->getHighestColumn();
//            //    echo $maxCol.'<br >';
//            //            echo get_class($rowIterator).'<br />';
//            //            $maxRow = $sheet->getHighestRow();
//            //            echo $maxRow.'<br />';
//            //            $rowCount = 0;
//            //            while($rowIterator->valid()) {
//            //                if($rowCount == $maxRow) {
//            //                    break;
//            //                }
//            //                echo $rowCount.'<br />';
//            //                $row = $rowIterator->current();
//            //echo $row->getRowIndex().'<br />';
//            //                echo get_class($row).'<br />';
//            //                $rowCount++;
//            //
//            //            }
//        }
//
//        //        echo 'processing: ' . $fileEntity->getOriginalFile() . '<br />';
//        //
//        //        $reader = $this->getReader($path);
//        //
//        //        echo get_class($reader) . '<br />';
//        //
//        //        $ret = $this->loadFile($reader, $path);
//
//        exit();
//
//        ob_start();
//
//        $reader = $this->getReader($path);
//        $ret    = $this->loadFile($reader, $path);
//        //$obj = $reader->load($path);
//        //        echo get_class($obj);
//        //        if($obj instanceof \PHPExcel) {
//        //
//        //        }
//
//        $msg = ob_get_clean();
//        $this->get('session')->getFlashBag()->add('info', $msg);
//
//        return true;
//
//        ob_start();
//        $fileType = $fileEntity->getFileType();
//
//        echo $fileType;
//
//        //        $phpExcel =  $this->get('phpexcel');
//        //
//        //        $excelObj = $this->get('phpexcel')->createPHPExcelObject($path);
//
//        $reader = \PHPExcel_IOFactory::createReaderForFile($path);
//        echo get_class($reader) . '--';
//        if ($reader instanceof \PHPExcel_Reader_CSV) {
//            $reader->setDdelimiter(';');
//        }
//        //        echo get_class($excelObj);
//        //
//        //        var_dump($excelObj->getSheetNames());
//        //
//        //        $iterator = $excelObj->getActiveSheet()->getRowIterator();
//        //
//        //        while ($iterator->valid()) {
//        //
//        //            $cIterator = $iterator->current()->getCellIterator();
//        //            while ($cIterator->valid()) {
//        //                echo $cIterator->current()->getValue().'<br />';
//        //                $cIterator->next();
//        //            }
//        //
//        //            $iterator->next();
//        //        }
//
//        $test = ob_get_clean();
//
//        $this->get('session')->getFlashBag()->add('info', $test);
//
//        //        if ($fileType == 'xls' || $fileType == 'xlsx') {
//        //            $excel = new \PHPExcel();
//        //        }
//        //
//        //        echo 'processing!!!';
//
//        return true;
//    }
//}