<?php

namespace Elektra\SeedBundle\Controller\Imports;

use Elektra\CrudBundle\Controller\Controller;
use Elektra\SeedBundle\Entity\EntityInterface;
use Elektra\SeedBundle\Entity\Imports\File;
use Elektra\SeedBundle\Entity\Imports\SeedUnit;

class SeedUnitController extends FileController
{

    /**
     * @return Definition
     */
    protected function getDefinition()
    {

        return $this->get('navigator')->getDefinition('Elektra', 'Seed', 'Imports', 'SeedUnit');
    }

    protected function processUploadedFile(File $fileEntity, $path)
    {

        ob_start();
        $fileType = $fileEntity->getFileType();


//        $phpExcel =  $this->get('phpexcel');
//
//        $excelObj = $this->get('phpexcel')->createPHPExcelObject($path);

        $reader = \PHPExcel_IOFactory::createReaderForFile($path);
        echo get_class($reader).'--';
if($reader instanceof \PHPExcel_Reader_CSV){
    $reader->setDelimiter(';');
}
//        echo get_class($excelObj);
//
//        var_dump($excelObj->getSheetNames());
//
//        $iterator = $excelObj->getActiveSheet()->getRowIterator();
//
//        while ($iterator->valid()) {
//
//            $cIterator = $iterator->current()->getCellIterator();
//            while ($cIterator->valid()) {
//                echo $cIterator->current()->getValue().'<br />';
//                $cIterator->next();
//            }
//
//            $iterator->next();
//        }

        $test = ob_get_clean();

        $this->get('session')->getFlashBag()->add('info', $test);

        //        if ($fileType == 'xls' || $fileType == 'xlsx') {
        //            $excel = new \PHPExcel();
        //        }
        //
        //        echo 'processing!!!';

        return true;
    }
}