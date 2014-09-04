<?php

namespace Elektra\CrudBundle\Table\Column;

use Elektra\CrudBundle\Table\Columns;

class CountColumn extends Column
{

    public function __construct(Columns $columns, $title)
    {

        parent::__construct($columns, $title);
        $this->setType('count');
    }



    protected function getCustomDisplayDataSingle($entry, $field, $rowNumber)
    {

        $method = 'get' . ucfirst($this->getFieldData());
        if (!method_exists($entry, $method)) {
            throw new \RuntimeException('field "' . $field . '" is not accessible at ' . get_class($entry));
        }
        $list   = $entry->$method();

        if (is_array($list) || $list instanceof \Countable) {
            return count($list);
        }

        throw new \RuntimeException('property "' . $field . '" is not countable at ' . get_class($entry));
    }
}