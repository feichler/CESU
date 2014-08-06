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
     * Constructor
     */
    public function __construct()
    {

        $this->css     = array();
        $this->classes = array(
            'table',
        );
    }

    public function setCss($property, $value)
    {

        $this->css[$property] = $value;
    }



    public function getCss()
    {

        $css = '';

        foreach ($this->css as $property => $value) {
            $css .= $property . ':' . $value . ';';
        }

        return $css;
    }

    public function addClass($class)
    {

        $this->classes[] = $class;
    }

    public function getClasses() {

        return implode(" ",$this->classes);
    }
}