<?php

namespace Elektra\ThemeBundle\Content;

abstract class Content
{

    protected $type;

    protected $content;

    protected $container;

    public function __construct($type)
    {

        $this->type      = $type;
        $this->content   = '';
        $this->container = null;
    }

    public function setContent($content)
    {

        $this->content = $content;
    }

    public function getContent()
    {

        $this->prepareContent();

        return $this->content;
    }

    protected function prepareContent()
    {

        // NOTE override this method if some preparation based on the values is needed
        // NOTE container setting gets prepared here, so be sure to call parent::prepareContent after the overriding processing

        $tag = '';
        switch ($this->container) {
            case 'p':
            case 'paragraph':
                $tag = 'p';
                break;
            case 'div':
                $tag = 'div';
                break;
            case 'span':
                $tag = 'span';
                break;
            case 'b':
            case 'bold':
            case 'strong':
                $tag = 'strong';
                break;
            case 'i':
            case 'italic':
            case 'em':
                $tag = 'em';
                break;
        }

        if ($tag != '') {
            $this->content = '<' . $tag . '>' . $this->content . '</' . $tag . '>';
        }
    }

    public function setContainer($container)
    {

        $this->container = $container;
    }

    public function getContainer()
    {

        return $this->container;
    }

    /**
     * @return mixed
     */
    public function getType()
    {

        return $this->type;
    }
}