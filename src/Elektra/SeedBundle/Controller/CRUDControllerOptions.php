<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Controller;

/**
 * Class CRUDControllerOptions
 *
 * CHECK: is this class needed anymore?
 *
 * @package Elektra\SeedBundle\Controller
 *
 * @version 0.1-dev
 */
class CRUDControllerOptions
{

    /**
     * @var array
     */
    protected $prefixes;

    /**
     * @var array
     */
    protected $classes;

    /**
     * @var string
     */
    protected $action;

    /**
     * @var array
     */
    protected $language;

    /**
     * @var int
     */
    protected $viewLimit = 25;

    /**
     *
     */
    public function __construct()
    {

        $this->prefixes = array(
            'route'      => '',
            'view'       => '',
            'viewCommon' => 'ElektraSeedBundle::',
        );
        $this->classes  = array(
            'entity'     => '',
            'table'      => '',
            'form'       => '',
            'repository' => '',
        );
        $this->language = array(
            'section' => '',
            'type'    => '',
        );
        $this->action   = '';
    }

    /**
     * @param string $type
     * @param string $class
     *
     * @throws \InvalidArgumentException
     */
    public function setClass($type, $class)
    {

        if (!array_key_exists($type, $this->classes)) {
            throw new \InvalidArgumentException('Unknown class type "' . $type . '"');
        }

        $this->classes[$type] = $class;
    }

    /**
     * @param string $type
     *
     * @return string
     */
    public function getClass($type)
    {

        return $this->classes[$type];
    }

    /**
     * @param string $type
     * @param string $prefix
     *
     * @throws \InvalidArgumentException
     */
    public function setPrefix($type, $prefix)
    {

        if (!array_key_exists($type, $this->prefixes)) {
            throw new \InvalidArgumentException('Unknown prefix type "' . $type . '"');
        }

        $this->prefixes[$type] = $prefix;
    }

    /**
     * @param string $type
     *
     * @return string
     */
    public function getPrefix($type)
    {

        return $this->prefixes[$type];
    }

    /**
     * @param string $action
     */
    public function setAction($action)
    {

        $this->action = $action;
    }

    /**
     * @return string
     */
    public function getAction()
    {

        return $this->action;
    }

    /**
     * @param string $section
     */
    public function setSection($section)
    {

        $this->language['section'] = $section;
    }

    /**
     * @return string
     */
    public function getSection()
    {

        return $this->language['section'];
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {

        $this->language['type'] = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {

        return $this->language['type'];
    }

    /**
     * @param int $viewLimit
     */
    public function setViewLimit($viewLimit)
    {

        $this->viewLimit = $viewLimit;
    }

    /**
     * @return int
     */
    public function getViewLimit()
    {

        return $this->viewLimit;
    }

    /**
     * @throws \BadMethodCallException
     */
    public function check()
    {

        // check if all classes are set up
        foreach ($this->classes as $type => $class) {
            if ($class == '') {
                throw new \BadMethodCallException('Check failed - required class of type "' . $type . '" is missing');
            }
        }

        // check if all prefixes are set up
        foreach ($this->prefixes as $type => $prefix) {
            if ($prefix == '') {
                throw new \BadMethodCallException('Check failed - required prefix of type "' . $type . '" is missing');
            }
        }

        // check if all languagees are set up
        foreach ($this->language as $type => $language) {
            if ($language == '') {
                throw new \BadMethodCallException('Check failed - required language setting of type "' . $type . '" is missing');
            }
        }

        if ($this->action == '') {
            throw new \BadMethodCallException('Check failed - action is missing');
        }
    }
}