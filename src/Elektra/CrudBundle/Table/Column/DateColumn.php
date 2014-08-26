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

    public function getDisplayData($entry)
    {

        $method    = 'get' . ucfirst($this->getFieldData());
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