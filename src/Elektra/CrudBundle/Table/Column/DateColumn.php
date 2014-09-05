<?php

namespace Elektra\CrudBundle\Table\Column;

use Elektra\CrudBundle\Table\Columns;
use Elektra\SeedBundle\Entity\AnnotableInterface;

class DateColumn extends Column
{

    protected $dateFormat = 'Y-m-d';

    protected $timeFormat = 'H:i:s';

    protected $timeZoneFormat = 'T (P)';

    //    protected $dateFormat = 'Y-m-d H:i:s P';

    public function __construct(Columns $columns, $title)
    {

        parent::__construct($columns, $title);
        $this->setType('date');
    }

    protected function getCustomDisplayDataSingle($entry, $field, $rowNumber)
    {

        $method = 'get' . ucfirst($this->getFieldData());
        if (!method_exists($entry, $method)) {
            throw new \RuntimeException('field "' . $field . '" is not accessible at ' . get_class($entry));
        }
        $timestamp = $entry->$method();

        $return = array(
            'dateGMT'     => gmdate($this->dateFormat, $timestamp),
            'timeGMT'     => gmdate($this->timeFormat, $timestamp),
            'timeZoneGMT' => gmdate($this->timeZoneFormat, $timestamp),
            // TODO add option for user time zone
        );

        return $return;

//        echo gmdate($this->dateFormat, $timestamp);
//
//        return gmdate($this->dateFormat, $timestamp);
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