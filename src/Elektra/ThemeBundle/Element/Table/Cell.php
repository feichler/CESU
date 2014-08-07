<?php

namespace Elektra\ThemeBundle\Element\Table;

class Cell
{

    /**
     * The cell's content
     *
     * @var array
     */
    protected $content;

    /**
     * The cell's css classes
     *
     * @var array
     */
    protected $classes;

    /**
     * The cells's css properties
     *
     * @var array
     */
    protected $css;

    /**
     * The cell's column span
     *
     * @var int
     */
    protected $colSpan;

    /**
     * The cell's row span
     *
     * @var int
     */
    protected $rowSpan;

    /**
     * Constructor
     */
    public function __construct()
    {

        $this->content = array();
        $this->classes = array();
        $this->css     = array();
        $this->colSpan = null;
        $this->rowSpan = null;
    }

    /**
     * Set the given CSS property
     *
     * @param string $property
     * @param string $value
     */
    protected function setCSS($property, $value)
    {

        $this->css[$property] = $value;
    }

    /**
     * Get the CSS properties to use within a HTML style tag
     *
     * @return string
     */
    public function getCss()
    {

        $css = '';

        foreach ($this->css as $property => $value) {
            $css .= $property . ':' . $value . ';';
        }

        return $css;
    }

    /**
     * Set the width of the cell
     *
     * @param int    $width
     * @param string $unit
     */
    public function setWidth($width, $unit = 'px')
    {

        $this->setCSS('width', $width . $unit);
    }

    /**
     * Set the cell's column span
     *
     * @param int $span
     */
    public function setColumnSpan($span)
    {

        $this->colSpan = $span;
    }

    /**
     * Get the cell's column span
     *
     * @return int|null
     */
    public function getColumnSpan()
    {

        return $this->colSpan;
    }

    /**
     * Set the cell's row span
     *
     * @param int $span
     */
    public function setRowSpan($span)
    {

        $this->rowSpan = $span;
    }

    /**
     * Get the cell's row span
     *
     * @return int|null
     */
    public function getRowSpan()
    {

        return $this->rowSpan;
    }

    public function addHTMLContent($html)
    {

        $item          = new \stdClass();
        $item->type    = 'html';
        $item->content = $html;

        $this->content[] = $item;
    }

    public function addActionContent($type, $params)
    {

        $item         = new \stdClass();
        $item->type   = 'action';
        $item->action = $type;
        $item->href   = $params[0];
        $item->text   = '';
        $item->render = '';
        if (isset($params[1])) {
            $item->text = $params[1];
        }
        if (isset($params[2])) {
            $item->render = $params[2];
        }
        $item->content = 'action.' . $type . '(';
        $item->content .= '"' . implode('","', $params) . '"';
        $item->content .= ')';

        $this->content[] = $item;
    }

    /**
     * Add content to the cell. HTML or special syntax for macros
     *
     * @param string $content
     */
    public function addContent($content)
    {

        if (preg_match('/^\{(.*)\}$/', $content, $matches)) {
            $params = explode('|', $matches[1]);
            $type   = explode(':', array_shift($params));
            //            echo 'got special content<br />';
            //            var_dump($type);
            //            var_dump($params);
            $call = '';
            if ($type[0] == 'action') {
                $call .= 'action.' . $type[1] . '(';
                $call .= '"' . $params[0] . '"';
                if (count($params) >= 2) {
                    $call .= ',"' . $params[1] . '"';
                } else {
                    $call .= ',""';
                }
                if (count($params) >= 3) {
                    $call .= ',"' . $params[2] . '"';
                } else {
                    $call .= ',""';
                }
                $call .= ')';
            }
            //            $this->content .= ' ' . $call;
            //            echo '<br />';
            //            echo $call . '<br />';
        } else {
            //            $this->content .= ' ' . $content;
        }
    }

    /**
     * Get the cell's content
     *
     * @return array
     */
    public function getContent()
    {

        return $this->content;
    }

    /**
     * Add a css class to the cell
     *
     * @param string $class
     */
    public function addClass($class)
    {

        $this->classes[] = $class;
    }

    /**
     * Set the row to the "active" styling
     */
    public function setActive()
    {

        $this->classes[] = 'active';
    }

    /**
     * Set the row to the "success" styling
     */
    public function setSuccess()
    {

        $this->classes[] = 'success';
    }

    /**
     * Set the row to the "info" styling
     */
    public function setInfo()
    {

        $this->classes[] = 'info';
    }

    /**
     * Set the row to the "warning" styling
     */
    public function setWarning()
    {

        $this->classes[] = 'warning';
    }

    /**
     * Set the row to the "error" styling
     */
    public function setError()
    {

        $this->classes[] = 'danger';
    }

    /**
     * Get the row's classes to use within a HTML class tag
     *
     * @return string
     */
    public function getClasses()
    {

        return implode(" ", $this->classes);
    }
}