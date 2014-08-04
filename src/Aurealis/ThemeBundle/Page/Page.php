<?php

namespace Aurealis\ThemeBundle\Page;

class Page
{

    protected $parameters;

    //    protected $scriptFiles = array('head' => array(), 'body' => array(),);
    //
    //    protected $scripts = array('head' => array(), 'body' => array(),);
    //
    //    protected $styleSheets = array();
    //
    //    protected $styles = array();
    //
    //

    public function __construct()
    {

        $this->parameters = array(
            'bundle'  => '',
            'body'    => array(
                'id'      => '',
                'classes' => array(),
            ),
            'scripts' => array(
                'jquery'    => false,
                'bootstrap' => false,
            ),
            'styles'  => array(
                'bootstrap_core'       => false,
                'bootstrap_extensions' => false,
                'font-awesome_core'    => false,
                'theme'                => false,
            ),
            'heading' => '',
        );
    }

    /* ##################################################################### *
     *  Setters / Includers / Adders
     * ##################################################################### */

    public function setBundle($bundle)
    {

        $this->parameters['bundle'] = $bundle;
    }

    public function setBodyId($id)
    {

        $this->parameters['body']['id'] = $id;
    }

    public function addBodyClass($class)
    {

        $this->parameters['body']['classes'][] = $class;
    }

    public function includeJQuery()
    {

        $this->parameters['scripts']['jquery'] = true;
    }

    public function includeEverything()
    {

        $this->includeBootstrapComplete();
        $this->includeFontAwesomeComplete();
        $this->includeThemeStyles();
    }

    public function includeFontAwesomeComplete()
    {

        $this->includeFontAwesomeStyles();
    }

    public function includeBootstrapComplete()
    {

        $this->includeBootstrapStyles('core');
        $this->includeBootstrapStyles('extensions');
        $this->includeBootstrapScripts();
    }

    public function includeBootstrapStyles($type = 'core')
    {

        $this->parameters['styles']['bootstrap_' . $type] = true;
    }

    public function includeFontAwesomeStyles()
    {

        $this->parameters['styles']['font-awesome_core'] = true;
    }

    public function includeThemeStyles()
    {

        $this->parameters['styles']['theme'] = true;
    }

    public function includeBootstrapScripts()
    {

        $this->includeJQuery();
        $this->parameters['scripts']['bootstrap'] = true;
    }

    public function setHeading($heading)
    {

        $this->parameters['heading'] = $heading;
    }

    /* ##################################################################### *
     *  Getters
     * ##################################################################### */

    public function getBundle()
    {

        return $this->parameters['bundle'];
    }

    public function getBodyId()
    {

        return $this->parameters['body']['id'];
    }

    public function getBodyClasses()
    {

        return $this->parameters['body']['classes'];
    }

    public function getScriptsJQuery()

    {

        return $this->parameters['scripts']['jquery'];
    }

    public function getScriptsBootstrap()
    {

        return $this->parameters['scripts']['bootstrap'];
    }

    public function getStylesBootstrapCore()
    {

        return $this->parameters['styles']['bootstrap_core'];
    }

    public function getStylesBootstrapExtensions()
    {

        return $this->parameters['styles']['bootstrap_extensions'];
    }

    public function getStylesFontAwesomeCore()
    {

        return $this->parameters['styles']['font-awesome_core'];
    }

    public function getStylesTheme()
    {

        return $this->parameters['styles']['theme'];
    }

    public function getHeading()
    {

        return $this->parameters['heading'];
    }
}