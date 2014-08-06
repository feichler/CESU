<?php

namespace Elektra\ThemeBundle\Element;

use Elektra\ThemeBundle\Element\Table\Style;

class Table
{

    const STYLE_STRIPED = 1;

    const STYLE_BORDERED = 2;

    const STYLE_HOVERROWS = 3;

    const STYLE_CONDENSED = 4;

    /**
     * @var Style
     */
    protected $style;

    protected $tableStyles = array();

    protected $width = '';

    protected $head;

    protected $foot;

    public function __construct()
    {

        $this->style = new Style();
    }

    public function setFullWidth()
    {

        $this->style->setCss('width', '100%');
        //        $this->width = '100%';
    }

    public function setAutoWidth()
    {

        $this->style->setCss('width', 'auto');
        //        $this->width = 'auto';
    }

    public function setWidth($width)
    {

        $this->style->setCss('width', $width);
        //        $this->width = $width;
    }

    public function setStriped()
    {

        $this->style->addClass('table-striped');
    }

    public function setBordered()
    {

        $this->style->addClass('table-bordered');
    }

    public function setHovering()
    {

        $this->style->addClass('table-hover');
    }

    public function setCondensed()
    {

        $this->style->addClass('table-condensed');
    }

    public function getStyle()
    {

        return $this->style;
    }

//    public function getWidth()
//    {
//
//        return $this->width;
//    }

//    public function getHasHead()
//    {
//
//        return false;
//    }

//    /**
//     * Set the table styles (use the STYLE_* constants)
//     *
//     * @param int $style
//     */
//    public function addTableStyle($style)
//    {
//
//        if (in_array($style, array(self::STYLE_STRIPED, self::STYLE_BORDERED, self::STYLE_HOVERROWS, self::STYLE_CONDENSED))) {
//            $this->tableStyles[] = $style;
//        }
//    }
//
//    public function getTableStyles()
//    {
//
//        return $this->tableStyles;
//    }
}