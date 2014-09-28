<?php

namespace Elektra\SeedBundle\Table\Imports;

use Elektra\CrudBundle\Table\Table;
use Elektra\SeedBundle\Table\Column\ImportActionColumn;

class ImportTable extends Table
{

    protected function initialiseActions()
    {

        //        $this->disallowAction('view');
        $this->disallowAction('edit');
        $this->disallowAction('delete');
    }

    protected function initialiseColumns()
    {

        $originalFile = $this->getColumns()->addTitleColumn('originalFile');
        $originalFile->setFieldData(array('originalFileName','serverFileName'));
        $originalFile->setSortable();

//        $serverFile = $this->getColumns()->add('serverFile');
//        $serverFile->setFieldData();
//        $serverFile->setSortable();

        $importType = $this->getColumns()->add('importType');
        $importType->setFieldData('importType');

        $entries = $this->getColumns()->add('entries');
        $entries->setFieldData('numberOfEntries');

//        $entriesProcessed = $this->getColumns()->add('entriesProcessed');
//        $entriesProcessed->setFieldData('numberOfEntriesProcessed');

//        $origFilename = $this->getColumns()->addTitleColumn('filename');
//        $origFilename->setFieldData('originalFile');
//        $origFilename->setSortable();
//
//        $newFileName = $this->getColumns()->add('new_filename');
//        $newFileName->setFieldData('uploadFile');
//
//        $processed = $this->getColumns()->add('processed');
//        $processed->setFieldData('processed');

        //        $actions = new ImportActionColumn($this->getColumns());
        //        $this->getColumns()->addColumn($actions, null);
    }
}