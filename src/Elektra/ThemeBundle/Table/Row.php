<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\ThemeBundle\Table;

/**
 * Class Row
 *
 * @package Elektra\ThemeBundle\Table
 *
 * @version 0.1-dev
 */
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
     *
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