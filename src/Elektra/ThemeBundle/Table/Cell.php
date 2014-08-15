<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\ThemeBundle\Table;

use Elektra\ThemeBundle\Content\Action;
use Elektra\ThemeBundle\Content\Content;
use Elektra\ThemeBundle\Content\HTML;

/**
 * Class Cell
 *
 * @package Elektra\ThemeBundle\Table
 *
 * @version 0.1-dev
 */
class Cell
{

    /**
     * @var array
     */
    protected $content;

    /**
     * @var array
     */
    protected $classes;

    /**
     * @var array
     */
    protected $style;

    /**
     * @var int|null
     */
    protected $colSpan;

    /**
     * @var int|null
     */
    protected $rowSpan;

    /**
     *
     */
    public function __construct()
    {

        $this->content = array();
        $this->classes = array();
        $this->style   = array();
        $this->colSpan = null;
        $this->rowSpan = null;
    }

    /**
     * @param string $property
     * @param string $value
     */
    protected function setStyle($property, $value)
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

    /**
     * @param int    $width
     * @param string $unit
     */
    public function setWidth($width, $unit = 'px')
    {

        $this->setStyle('width', $width . $unit);
    }

    /**
     * @param int $span
     */
    public function setColumnSpan($span)
    {

        $this->colSpan = $span;
    }

    /**
     * @return int|null
     */
    public function getColumnSpan()
    {

        return $this->colSpan;
    }

    /**
     * @param int $span
     */
    public function setRowSpan($span)
    {

        $this->rowSpan = $span;
    }

    /**
     * @return int|null
     */
    public function getRowSpan()
    {

        return $this->rowSpan;
    }

    /**
     * @param string $class
     */
    public function addClass($class)
    {

        $this->classes[] = $class;
    }

    /**
     *
     */
    public function setActive()
    {

        $this->addClass('active');
    }

    /**
     *
     */
    public function setSuccess()
    {

        $this->addClass('success');
    }

    /**
     *
     */
    public function setInfo()
    {

        $this->addClass('info');
    }

    /**
     *
     */
    public function setWarning()
    {

        $this->addClass('warning');
    }

    /**
     *
     */
    public function setError()
    {

        $this->addClass('danger');
    }

    /**
     * @return array
     */
    public function getClasses()
    {

        return $this->classes;
    }

    /**
     * @param Content $content
     */
    public function addContent(Content $content)
    {

        $this->content[] = $content;
    }

    /**
     * @param string $content
     *
     * @return HTML
     */
    public function addHtmlContent($content)
    {

        $item = new HTML();
        $item->setContent($content);

        $this->addContent($item);

        return $item;
    }

    /**
     * @param string $action
     * @param string $link
     * @param array  $params
     *
     * @return Action
     */
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

    /**
     * @return array
     */
    public function getContent()
    {

        return $this->content;
    }
}