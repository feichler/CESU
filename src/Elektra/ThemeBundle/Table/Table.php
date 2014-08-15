<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\ThemeBundle\Table;

use Elektra\ThemeBundle\Pagination\Pagination;

/**
 * Class Table
 *
 * @package Elektra\ThemeBundle\Table
 *
 * @version 0.1-dev
 */
class Table
{

    /**
     * @var Style
     */
    protected $style;

    /**
     * @var Pagination
     */
    protected $pagination;

    /**
     * @var array
     */
    protected $header;

    /**
     * @var array
     */
    protected $footer;

    /**
     * @var array
     */
    protected $content;

    /**
     * @var array
     */
    protected $params;

    /**
     *
     */
    public function __construct()
    {

        $this->style      = new Style();
        $this->pagination = new Pagination();
        $this->header     = array();
        $this->footer     = array();
        $this->content    = array();
        $this->params     = array();
    }

    /**
     * @return Style
     */
    public function getStyle()
    {

        return $this->style;
    }

    /**
     * @return Pagination
     */
    public function getPagination()
    {

        return $this->pagination;
    }

    /**
     * @return Row
     */
    public function addHeader()
    {

        $row            = new Row();
        $this->header[] = $row;

        return $row;
    }

    /**
     * @return array
     */
    public function getHeader()
    {

        return $this->header;
    }

    /**
     * @return bool
     */
    public function getHasHeader()
    {

        return count($this->header) != 0;
    }

    /**
     * @return Row
     */
    public function addFooter()
    {

        $row            = new Row();
        $this->footer[] = $row;

        return $row;
    }

    /**
     * @return array
     */
    public function getFooter()
    {

        return $this->footer;
    }

    /**
     * @return bool
     */
    public function getHasFooter()
    {

        return count($this->footer) != 0;
    }

    /**
     * @return Row
     */
    public function addContent()
    {

        $row             = new Row();
        $this->content[] = $row;

        return $row;
    }

    /**
     * @return array
     */
    public function getContent()
    {

        return $this->content;
    }

    /**
     * @return bool
     */
    public function getHasContent()
    {

        return count($this->content) != 0;
    }

    /**
     * @return int
     */
    public function getColumnCount()
    {

        $cols = 0;

        foreach ($this->header as $row) {
            $rowCount = $this->getRowCellCount($row);
            if ($rowCount > $cols) {
                $cols = $rowCount;
            }
        }
        foreach ($this->footer as $row) {
            $rowCount = $this->getRowCellCount($row);
            if ($rowCount > $cols) {
                $cols = $rowCount;
            }
        }
        foreach ($this->content as $row) {
            $rowCount = $this->getRowCellCount($row);
            if ($rowCount > $cols) {
                $cols = $rowCount;
            }
        }

        return $cols;
    }

    /**
     * @param Row $row
     *
     * @return int
     */
    private function getRowCellCount(Row $row)
    {

        $count = 0;

        foreach ($row->getCells() as $cell) {
            $colSpan = $cell->getColumnSpan();
            if ($colSpan !== null) {
                $count += $colSpan;
            } else {
                $count++;
            }
        }

        return $count;
    }

    /**
     * @param string $name
     * @param string $value
     */
    private function setParameter($name, $value)
    {

        $this->params[$name] = $value;
    }

    /**
     * @param string $name
     *
     * @return mixed|null
     */
    public function getParameter($name)
    {

        if (array_key_exists($name, $this->params)) {
            return $this->params[$name];
        }

        return null;
    }

    /**
     * @return array
     */
    public function getParameters()
    {

        return $this->params;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {

        $this->setParameter('id', $id);
    }
}