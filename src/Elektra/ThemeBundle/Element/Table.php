<?php

namespace Elektra\ThemeBundle\Element;

use Elektra\ThemeBundle\Element\Table\Foot;
use Elektra\ThemeBundle\Element\Table\Head;
use Elektra\ThemeBundle\Element\Table\Row;
use Elektra\ThemeBundle\Element\Table\Style;
use Symfony\Component\Routing\RouterInterface;

class Table
{

    /**
     * @var string
     */
    protected $routingPrefix;

    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * Styling information of this table element
     *
     * @var Style
     */
    protected $style;

    /**
     * Message indicating "no-rows" in the table
     *
     * @var string
     */
    protected $emptyContentMessage;

    /**
     * Array of header rows
     *
     * @var array
     */
    protected $headerRows;

    /**
     * Array of footer rows
     *
     * @var array
     */
    protected $footerRows;

    /**
     * Array of content rows
     *
     * @var array
     */
    protected $contentRows;

    /**
     * Constructor
     */
    public function __construct(RouterInterface $router)
    {

        $this->router              = $router;
        $this->emptyContentMessage = 'table.entries.none';
        $this->style               = new Style();
        $this->headerRows          = array();
        $this->footerRows          = array();
        $this->contentRows         = array();
    }

    protected function setRoutingPrefix($routingPrefix)
    {

        $this->routingPrefix = $routingPrefix;
    }

    protected function getRouteName($type)
    {

        return $this->routingPrefix . '_' . $type;
    }

    public function defaultStyling()
    {

        $this->style->setFullWidth();
        $this->style->setStriped();
        $this->style->setResponsive();
        $this->style->setBordered();
    }

    /**
     * Add a row to the header
     *
     * @return Row
     */
    public function addHeaderRow()
    {

        $row = new Row();

        $this->headerRows[] = $row;

        return $row;
    }

    /**
     * Returns <code>true</code> if the table has header rows.
     *
     * @return bool
     */
    public function getHasHeader()
    {

        return count($this->headerRows) != 0;
    }

    /**
     * Get the header rows
     *
     * @return array
     */
    public function getHeaderRows()
    {

        return $this->headerRows;
    }

    /**
     * Add a row to the footer
     *
     * @return Row
     */
    public function addFooterRow()
    {

        $row = new Row();

        $this->footerRows[] = $row;

        return $row;
    }

    /**
     * Returns <code>true</code> if the table has footer rows.
     *
     * @return bool
     */
    public function getHasFooter()
    {

        return count($this->footerRows) != 0;
    }

    /**
     * Get the footer rows
     *
     * @return array
     */
    public function getFooterRows()
    {

        return $this->footerRows;
    }

    /**
     * Add a row to the content
     *
     * @return Row
     */
    public function addContentRow()
    {

        $row = new Row();

        $this->contentRows[] = $row;

        return $row;
    }

    /**
     * Returns <code>true</code> if the table has content rows.
     *
     * @return bool
     */
    public function getHasContent()
    {

        return count($this->contentRows) != 0;
    }

    /**
     * Get the content rows
     *
     * @return array
     */
    public function getContentRows()
    {

        return $this->contentRows;
    }

    /**
     * Get the empty table message
     *
     * @return string
     */
    public function getEmptyContentMessage()
    {

        return $this->emptyContentMessage;
    }

    /**
     * Get the style object for this table
     *
     * @return Style
     */
    public function getStyle()
    {

        return $this->style;
    }

    /**
     * Get the column count of the table (maximum columns in any of the stored rows)
     *
     * @return int
     */
    public function getColumnCount()
    {

        $maxColumns = 0;

        foreach ($this->headerRows as $row) {
            if (count($row->getCells()) > $maxColumns) {
                $maxColumns = count($row->getCells());
            }
        }
        foreach ($this->footerRows as $row) {
            if (count($row->getCells()) > $maxColumns) {
                $maxColumns = count($row->getCells());
            }
        }
        foreach ($this->contentRows as $row) {
            if (count($row->getCells()) > $maxColumns) {
                $maxColumns = count($row->getCells());
            }
        }

        return $maxColumns;
    }

    //
    //    /**
    //     * Table Head
    //     *
    //     * @var Head
    //     */
    //    protected $head;
    //
    //    /**
    //     * Table Foot
    //     *
    //     * @var Foot
    //     */
    //    protected $foot;
    //
    //    /**
    //     * Constructor
    //     */
    //    public function __construct()
    //    {
    //
    //        $this->style = new Style();
    //        $this->head  = new Head();
    //        $this->foot  = new Foot();
    //    }
    //

    //
    //    /**
    //     * Get the head object for this table
    //     *
    //     * @return Head
    //     */
    //    public function getHead()
    //    {
    //
    //        return $this->head;
    //    }
    //
    //    /**
    //     * Check if the table has a head to display
    //     *
    //     * @return bool
    //     */
    //    public function getHasHead()
    //    {
    //
    //        return $this->head->getColumnCount() != 0;
    //    }
    //
    //    /**
    //     * Get the foot object for this table
    //     *
    //     * @return Foot
    //     */
    //    public function getFoot()
    //    {
    //
    //        return $this->foot;
    //    }

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