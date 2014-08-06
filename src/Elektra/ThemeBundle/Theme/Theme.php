<?php

namespace Elektra\ThemeBundle\Theme;

class Theme
{

    /**
     * Array of scripts to include
     *
     * @var array
     */
    protected $scripts;

    /**
     * Array of stylesheets to include
     *
     * @var array
     */
    protected $stylesheets;

    /**
     * Array of page parameters
     *
     * @var array
     */
    protected $page;

    /**
     * Constructor
     */
    public function __construct()
    {

        // define the common / known variables

        $this->scripts = array(
            'jquery'    => false,
            'bootstrap' => false,
        );

        $this->stylesheets = array(
            'bootstrap'   => false,
            'fontAwesome' => false,
        );

        $this->page = array(
            'title'   => '',
            'heading' => '',
        );
    }

    /**
     * Set a script to include (set the flag)
     *
     * @param string $script
     */
    public function setScript($script)
    {

        $this->scripts[$script] = true;
    }

    /**
     * Get a script to include (get the flag)
     *
     * @param string $script
     *
     * @return bool
     */
    public function getScript($script)
    {

        if (array_key_exists($script, $this->scripts)) {
            return $this->scripts[$script];
        }

        return false;
    }

    /**
     * Set a stylesheet to include (set the flag)
     *
     * @param string $stylesheet
     */
    public function setStylesheet($stylesheet)
    {

        $this->stylesheets[$stylesheet] = true;
    }

    /**
     * Get a stylesheet to include (get the flag)
     *
     * @param string $stylesheet
     *
     * @return bool
     */
    public function getStylesheet($stylesheet)
    {

        if (array_key_exists($stylesheet, $this->stylesheets)) {
            return $this->stylesheets[$stylesheet];
        }

        return false;
    }

    /**
     * Set a page variable
     *
     * @param string $var
     * @param mixed  $content
     */
    public function setPageVar($var, $content)
    {

        $this->page[$var] = $content;
    }

    /**
     * Get a page variable
     *
     * @param string $var
     *
     * @return mixed
     */
    public function getPageVar($var)
    {

        if (array_key_exists($var, $this->page)) {
            return $this->page[$var];
        }

        return null;
    }
}