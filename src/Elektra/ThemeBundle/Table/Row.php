<?php

namespace Elektra\ThemeBundle\Table;

class Row
{

    /**
     * @var array
     */
    protected $cells;

    /**
     * @var array
     */
    protected $classes;

    /**
     * Constructor
     */
    public function __construct()
    {

        $this->cells   = array();
        $this->classes = array();
    }

    /**
     * @return Cell
     */
    public function addCell()
    {

        $cell          = new Cell();
        $this->cells[] = $cell;

        return $cell;
    }

    /**
     * @return array
     */
    public function getCells()
    {

        return $this->cells;
    }

    /**
     * @param string $class
     */
    public function addClass($class)
    {

        $this->classes[] = $class;
    }

    /**
     *
     */
    public function setActive()
    {

        $this->addClass('active');
    }

    /**
     *
     */
    public function setSuccess()
    {

        $this->addClass('success');
    }

    /**
     *
     */
    public function setInfo()
    {

        $this->addClass('info');
    }

    /**
     *
     */
    public function setWarning()
    {

        $this->addClass('warning');
    }

    /**
     *
     */
    public function setError()
    {

        $this->addClass('danger');
    }

    /**
     * @return array
     */
    public function getClasses()
    {

        return $this->classes;
    }
}