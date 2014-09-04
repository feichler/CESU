<?php

namespace Elektra\CrudBundle\Table\Column;

use Elektra\CrudBundle\Table\Columns;

class TitleColumn extends Column
{

    public function __construct(Columns $columns, $title)
    {

        parent::__construct($columns, $title);
        $this->setType('title');
        $this->setFieldData('title');
    }

    public function getDisplayData($entry, $rowNumber)
    {

        $table = $this->getColumns()->getTable();
        $data  = parent::getDisplayData($entry, $rowNumber);

        if (is_array($data)) {
            $title = array_shift($data);
        } else {
            $title = $data;
        }

        // URGENT get the right link here
        $link = '';
        if ($this->getColumns()->getTable()->isAllowed('view')) {
            $link = $table->getCrud()->getLinker()->getListViewLink($entry);
        }

        $return = array(
            'title' => $title,
            'link'  => $link,
            'other' => array(),
        );

        if (is_array($data)) {
            $return['other'] = $data;
        }

        return $return;
    }
}