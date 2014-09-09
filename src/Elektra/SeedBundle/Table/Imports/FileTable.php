<?php

namespace Elektra\SeedBundle\Table\Imports;

use Elektra\CrudBundle\Table\Table;

class FileTable extends Table
{

    protected function initialiseActions()
    {

        $this->disallowAction('view');
        $this->disallowAction('edit');
        $this->disallowAction('delete');
    }

    protected function initialiseColumns()
    {

        $origFilename = $this->getColumns()->addTitleColumn('filename');
        $origFilename->setFieldData('originalFile');
        $origFilename->setSortable();

        $newFileName = $this->getColumns()->add('new_filename');
        $newFileName->setFieldData('uploadFile');

        $processed = $this->getColumns()->add('processed');
        $processed->setFieldData('processed');
    }
}