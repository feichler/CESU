<?php

namespace Elektra\CrudBundle\Table\Column;

use Elektra\CrudBundle\Table\Columns;

class ActionColumn extends Column
{

    public function __construct(Columns $columns)
    {

        parent::__construct($columns, 'tables.generic.columns.action');
        $this->setType('action');
    }

    public function getDisplayData($entry)
    {

        $return      = array();
        $table       = $this->getColumns()->getTable();
        $languageKey = $table->getCrud()->getLanguageKey();
        if ($table->isAllowed('edit')) {
            $link           = $table->getCrud()->getLinker()->getListEditLink($entry);
            $return['edit'] = array(
                'link'    => $link,
                'confirm' => false,
                'langKey' => $languageKey,
            );
        }

        if ($table->isAllowed('delete')) {
            $link             = $table->getCrud()->getLinker()->getListDeleteLink($entry);
            $return['delete'] = array(
                'link'    => $link,
                'confirm' => true,
                'langKey' => $languageKey,
            );
        }

        // URGENT implement method

        return $return;
    }
}