<?php

namespace Elektra\CrudBundle\Table\Column;

use Elektra\CrudBundle\Table\Columns;

class ActionColumn extends Column
{

    public function __construct(Columns $columns)
    {

        parent::__construct($columns, 'table.columns.action');
        $this->setType('action');
    }

    public function getDisplayData($entry)
    {

        $return = array();

        $table = $this->getColumns()->getTable();
        //        $container = $table->getContainer();

        $langKey = $table->getSpecificLangKey();

        if ($table->isAllowed('edit')) {
            $return['edit'] = array(
                'link'    => $table->getCrud()->getEditLink($entry),
                'confirm' => false,
                'langKey' => $langKey,
            );
        }

        if ($table->isAllowed('delete')) {
            $return['delete'] = array(
                'link'    => $table->getCrud()->getDeleteLink($entry),
                'confirm' => true,
                'langKey' => $langKey,
            );
        }

        return $return;
    }
}