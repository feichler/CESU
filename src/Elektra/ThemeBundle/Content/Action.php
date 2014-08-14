<?php

namespace Elektra\ThemeBundle\Content;

class Action extends Content
{

    protected $action;

    protected $link;

    protected $text;

    protected $options;

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

//    protected function prepareContent()
//    {
//
////        $content = 'actions.' . $this->action . '(';
////        $content .= '"' . $this->link . '",';
////        $content .= '"' . $this->text . '",';
////
////        $content .= '{';
////        foreach ($this->options as $key => $value) {
////            $content .= '"' . $key . '": "' . $value . '",';
////        }
////        $content .= '}';
////
////        $content .= ')';
//    }
}