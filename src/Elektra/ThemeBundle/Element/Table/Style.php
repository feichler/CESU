<?php

namespace Elektra\ThemeBundle\Element\Table;

class Style
{

    /**
     * @var array
     */
    protected $css;

    /**
     * @var array
     */
    protected $classes;

    /**
     * @var bool
     */
    protected $responsive;

    /**
     * Constructor
     */
    public function __construct()
    {

        $this->css        = array();
        $this->classes    = array(
            'table',
        );
        $this->responsive = false;
    }

    /**
     * Set the given CSS property
     *
     * @param string $property
     * @param string $value
     */
    protected function setCSS($property, $value)
    {

        $this->css[$property] = $value;
    }

    /**
     * Get the CSS properties to use within a HTML style tag
     *
     * @return string
     */
    public function getCss()
    {

        $css = '';

        foreach ($this->css as $property => $value) {
            $css .= $property . ':' . $value . ';';
        }

        return $css;
    }

    /**
     * Add a class to this table's class list
     *
     * @param $class
     */
    protected function addClass($class)
    {

        $this->classes[] = $class;
    }

    /**
     * Get the table's classes to use within a HTML class tag
     *
     * @return string
     */
    public function getClasses()
    {

        return implode(" ", $this->classes);
    }

    /**
     * Set the table to full-width
     */
    public function setFullWidth()
    {

        $this->setCSS('width', '100%');
    }

    /**
     * Set the table to auto-width
     */
    public function setAutoWidth()
    {

        $this->setCSS('width', 'auto');
    }

    /**
     * Set the table's width to the desire value
     *
     * @param int    $width
     * @param string $unit
     */
    public function setWidth($width, $unit = 'px')
    {

        $this->setCSS('width', $width . $unit);
    }

    /**
     * Set the table's layout to "striped"
     */
    public function setStriped()
    {

        $this->addClass('table-striped');
    }

    /**
     * Set the table's layout to "bordered"
     */
    public function setBordered()
    {

        $this->addClass('table-bordered');
    }

    /**
     * Set the table's layout to "hover"
     */
    public function setHover()
    {

        $this->addClass('table-hover');
    }

    /**
     * Set the table's layout to "condensed"
     */
    public function setCondensed()
    {

        $this->addClass('table-condensed');
    }

    /**
     * Set the table's layout to "responsive"
     */
    public function setResponsive()
    {

        $this->responsive = true;
    }

    /**
     * Does the table's layout contain the "responsive" layout?
     *
     * @return bool
     */
    public function getResponsive()
    {

        return $this->responsive;
    }
}