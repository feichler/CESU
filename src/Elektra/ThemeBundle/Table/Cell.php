<?php

namespace Elektra\ThemeBundle\Table;

use Elektra\ThemeBundle\Content\Action;
use Elektra\ThemeBundle\Content\Content;
use Elektra\ThemeBundle\Content\HTML;

class Cell
{

    protected $content;

    protected $classes;

    protected $style;

    protected $colSpan;

    protected $rowSpan;

    public function __construct()
    {

        $this->content = array();
        $this->classes = array();
        $this->style   = array();
        $this->colSpan = null;
        $this->rowSpan = null;
    }

    protected function setStyle($property, $value)
    {

        $this->style[$property] = $value;
    }

    public function getStyle()
    {

        return $this->style;
    }

    public function setWidth($width, $unit = 'px')
    {

        $this->setStyle('width', $width . $unit);
    }

    public function setColumnSpan($span)
    {

        $this->colSpan = $span;
    }

    public function getColumnSpan()
    {

        return $this->colSpan;
    }

    public function setRowSpan($span)
    {

        $this->rowSpan = $span;
    }

    public function getRowSpan()
    {

        return $this->rowSpan;
    }

    public function addClass($class)
    {

        $this->classes[] = $class;
    }

    public function setActive()
    {

        $this->addClass('active');
    }

    public function setSuccess()
    {

        $this->addClass('success');
    }

    public function setInfo()
    {

        $this->addClass('info');
    }

    public function setWarning()
    {

        $this->addClass('warning');
    }

    public function setError()
    {

        $this->addClass('danger');
    }

    public function getClasses()
    {

        return $this->classes;
    }

    public function addContent(Content $content)
    {

        $this->content[] = $content;
    }

    public function addHtmlContent($content)
    {

        $item = new HTML();
        $item->setContent($content);

        $this->addContent($item);

        return $item;
    }

    public function addActionContent($action, $link, $params = array())
    {

        $item = new Action();
        $item->setAction($action);
        $item->setLink($link);

        if (isset($params['text'])) {
            $item->setText($params['text']);
        }

        if (isset($params['render'])) {
            $item->addOption('render', $params['render']);
        }

        if (isset($params['confirmMsg'])) {
            $item->addOption('confirmMsg', $params['confirmMsg']);
        }

        $this->addContent($item);

        return $item;
    }

    public function getContent()
    {

        return $this->content;
    }
}