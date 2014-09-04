<?php

namespace Elektra\CrudBundle\Table\Column;

use Elektra\CrudBundle\Table\Columns;
use Elektra\SeedBundle\Entity\AnnotableInterface;

class DateColumn extends Column
{

    protected $dateFormat = 'Y-m-d H:i:s P';

    public function __construct(Columns $columns, $title)
    {

        parent::__construct($columns, $title);
        $this->setType('date');
    }

    protected function getCustomDisplayDataSingle($entry, $field, $rowNumber)
    {

        $method    = 'get' . ucfirst($this->getFieldData());
        if (!method_exists($entry, $method)) {
            throw new \RuntimeException('field "' . $field . '" is not accessible at ' . get_class($entry));
        }
        $timestamp = $entry->$method();

        return date($this->dateFormat, $timestamp);
    }

    /**
     * @param string $dateFormat
     */
    public function setDateFormat($dateFormat)
    {

        $this->dateFormat = $dateFormat;
    }

    /**
     * @return string
     */
    public function getDateFormat()
    {

        return $this->dateFormat;
    }
}