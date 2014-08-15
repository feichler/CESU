<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\ThemeBundle\Content;

/**
 * Class Action
 *
 * @package Elektra\ThemeBundle\Content
 *
 * @version 0.1-dev
 */
class Action extends Content
{

    /**
     * @var string
     */
    protected $action;

    /**
     * @var string
     */
    protected $link;

    /**
     * @var string
     */
    protected $text;

    /**
     * @var array
     */
    protected $options;

    /**
     *
     */
    public function __construct()
    {

        parent::__construct('action');

        $this->action  = '';
        $this->link    = '';
        $this->text    = '';
        $this->options = array();
    }

    /**
     * @param mixed $action
     */
    public function setAction($action)
    {

        $this->action = $action;
    }

    /**
     * @return mixed
     */
    public function getAction()
    {

        return $this->action;
    }

    /**
     * @param mixed $link
     */
    public function setLink($link)
    {

        $this->link = $link;
    }

    /**
     * @return mixed
     */
    public function getLink()
    {

        return $this->link;
    }

    /**
     * @param string $text
     */
    public function setText($text)
    {

        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getText()
    {

        return $this->text;
    }

    /**
     * @param string $option
     * @param mixed  $value
     */
    public function addOption($option, $value)
    {

        $this->options[$option] = $value;
    }

    /**
     * @return array
     */
    public function getOptions()
    {

        return $this->options;
    }
}