<?php

namespace Elektra\CrudBundle\Table\Column;

use Elektra\CrudBundle\Table\Columns;

class TitleColumn extends Column
{

    public function __construct(Columns $columns, $title)
    {

        parent::__construct($columns, $title);
        $this->setType('title');
    }

    public function getDisplayData($entry)
    {

        $table     = $this->getColumns()->getTable();
        $data      = parent::getDisplayData($entry);

        if (is_array($data)) {
            $title = array_shift($data);
        } else {
            $title = $data;
        }

        $link = $table->getCrud()->getViewLink($entry);

        $return = array(
            'title' => $title,
            'link' => $link,
            'other' => array(),
        );

        if(is_array($data)) {
            $return['other'] = $data;
        }

        return $return;

    }
}