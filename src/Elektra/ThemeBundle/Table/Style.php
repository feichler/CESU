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
 * Class Style
 *
 * @package Elektra\ThemeBundle\Table
 *
 * @version 0.1-dev
 */
class Style
{

    /**
     * @var array
     */
    private $validStyles = array(
        'table-striped'    => array('stripe', 'striped'),
        'table-bordered'   => array('border', 'bordered'),
        'table-hover'      => array('hover', 'hovered', 'hovering'),
        'table-condensed'  => array('condensed', 'small'),
        'table-responsive' => array('responsive'),
    );

    /**
     * @var array
     */
    protected $classes;

    /**
     * @var array
     */
    protected $params;

    /**
     * @var array
     */
    protected $style;

    /**
     *
     */
    public function __construct()
    {

        $this->classes = array('table');
        $this->params  = array(
            'responsive' => false,
        );
        $this->style   = array();
    }

    /**
     *
     */
    public function setFullWidth()
    {

        $this->setWidth(100, '%');
    }

    /**
     *
     */
    public function setAutoWidth()
    {

        $this->setStyle('width', 'auto');
    }

    /**
     * @param int|float $width
     * @param string    $unit
     */
    public function setWidth($width, $unit = 'px')
    {

        $this->setStyle('width', $width . $unit);
    }

    /**
     * @param string $style
     */
    public function setVariant($style)
    {

        foreach ($this->validStyles as $class => $styles) {
            if (in_array($style, $styles)) {
                // valid styling
                if ($class == 'table-responsive') {
                    // responsive styling is not direct at the table
                    $this->setParameter('responsive', true);
                } else {
                    $this->addClass($class);
                }
            }
        }
    }

    /**
     * @param string $class
     */
    private function addClass($class)
    {

        $this->classes[] = $class;
    }

    /**
     * @return array
     */
    public function getClasses()
    {

        return $this->classes;
    }

    /**
     * @param string $name
     * @param string $value
     */
    private function setParameter($name, $value)
    {

        $this->params[$name] = $value;
    }

    /**
     * @param string $name
     *
     * @return mixed|null
     */
    public function getParameter($name)
    {

        if (array_key_exists($name, $this->params)) {
            return $this->params[$name];
        }

        return null;
    }

    /**
     * @param string $property
     * @param string $value
     */
    private function setStyle($property, $value)
    {

        $this->style[$property] = $value;
    }

    /**
     * @return array
     */
    public function getStyle()
    {

        return $this->style;
    }
}