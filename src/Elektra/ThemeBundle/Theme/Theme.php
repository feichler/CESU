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
     * Array of page variables (mostly content)
     *
     * @var array
     */
    protected $pageVars;

    /**
     * Array of all other variables that may be used by the template
     *
     * @var array
     */
    protected $vars;

    /**
     * Array of page parts (sub-templates)
     *
     * @var array
     */
    protected $subTemplates;

    /**
     * @var string
     */
    protected $activeRoute = '';

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

        $this->pageVars = array(
            'title'      => null,
            'heading'    => null,
            'subheading' => null,
        );

        $this->vars = array();

        $this->subTemplates = array(
            'navbar' => null,
            'footer' => null,
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

        $this->pageVars[$var] = $content;
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

        if (array_key_exists($var, $this->pageVars)) {
            return $this->pageVars[$var];
        }

        return null;
    }

    /**
     * Set a variable
     *
     * @param string $var
     * @param mixed  $content
     */
    public function setVar($var, $content)
    {

        $this->vars[$var] = $content;
    }

    /**
     * Get a variable
     *
     * @param string $var
     *
     * @return mixed
     */
    public function getVar($var)
    {

        if (array_key_exists($var, $this->vars)) {
            return $this->vars[$var];
        }

        return null;
    }

    /**
     * Set the given template identifier
     *
     * @param string $type
     * @param string $identifier
     */
    public function setSubTemplate($type, $identifier)
    {

        $this->subTemplates[$type] = $identifier;
    }

    /**
     * Reset the given template identifier (in order to omit the rendering)
     *
     * @param string $type
     */
    public function resetSubTemplate($type)
    {

        $this->subTemplates[$type] = null;
    }

    /**
     * Get the required template identifier
     *
     * @param string $type
     *
     * @return string|null
     */
    public function getSubTemplate($type)
    {

        if (array_key_exists($type, $this->subTemplates)) {
            return $this->subTemplates[$type];
        }

        return null;
    }

    /**
     * Set the active route for menu highlighting
     *
     * @param string $route
     */
    public function setActiveRoute($route)
    {

        $this->activeRoute = $route;
    }

    /**
     * Get the active route
     *
     * @return string
     */
    public function getActiveRoute()
    {

        return $this->activeRoute;
    }

    /**
     * Checks if the given route is the active one (for menu highlighting)
     *
     * @param string $route
     *
     * @return bool
     */
    public function isActive($route)
    {

        if ($this->activeRoute == $route) {
            return true;
        }

        return false;
    }
}