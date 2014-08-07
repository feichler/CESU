<?php

namespace Elektra\ThemeBundle\Element\Table;

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
     * Add a cell to the row. Returns the generated cell for further processing
     *
     * @return Cell
     */
    public function addCell()
    {

        $cell = new Cell();

        $this->cells[] = $cell;

        return $cell;
    }

    /**
     * Get the cells for this row
     *
     * @return array
     */
    public function getCells()
    {

        return $this->cells;
    }

    /**
     * Set the row to the "active" styling
     */
    public function setActive()
    {

        $this->classes[] = 'active';
    }

    /**
     * Set the row to the "success" styling
     */
    public function setSuccess()
    {

        $this->classes[] = 'success';
    }

    /**
     * Set the row to the "info" styling
     */
    public function setInfo()
    {

        $this->classes[] = 'info';
    }

    /**
     * Set the row to the "warning" styling
     */
    public function setWarning()
    {

        $this->classes[] = 'warning';
    }

    /**
     * Set the row to the "error" styling
     */
    public function setError()
    {

        $this->classes[] = 'danger';
    }

    /**
     * Get the row's classes to use within a HTML class tag
     *
     * @return string
     */
    public function getClasses()
    {

        return implode(" ", $this->classes);
    }
}