<?php

namespace Elektra\CrudBundle\Table;

use Elektra\CrudBundle\Table\Column\Column;
use Elektra\CrudBundle\Table\Column\ActionColumn;
use Elektra\CrudBundle\Table\Column\AuditColumn;
use Elektra\CrudBundle\Table\Column\CountColumn;
use Elektra\CrudBundle\Table\Column\DateColumn;
use Elektra\CrudBundle\Table\Column\IdColumn;
use Elektra\CrudBundle\Table\Column\NoteColumn;
use Elektra\CrudBundle\Table\Column\SelectColumn;
use Elektra\CrudBundle\Table\Column\TitleColumn;

class Columns
{

    /**
     * @var Table
     */
    protected $table;

    /**
     * @var array
     */
    protected $columns;

    /**
     * @var array
     */
    protected $flags;

    /**
     * @var array
     */
    protected $flaggedColumns;

    /**
     * @param Table $table
     */
    public function __construct(Table $table)
    {

        $this->table          = $table;
        $this->columns        = array();
        $this->flags          = array();
        $this->flaggedColumns = array();
    }

    /*************************************************************************
     * Common Getters / Setters
     *************************************************************************/

    /**
     * @return Table
     */
    public function getTable()
    {

        return $this->table;
    }

    /*************************************************************************
     * Column related functions
     *************************************************************************/

    /**
     * @param string          $title
     * @param int|string|null $index
     *
     * @return Column
     */
    public function add($title, $index = null)
    {

        $column = new Column($this, $title);

        return $this->addColumn($column, $index);
    }

    /**
     * @param Column          $column
     * @param int|string|null $index
     *
     * @return Column
     */
    public function addColumn(Column $column, $index)
    {

        if (is_int($index)) {
            $this->columns[$index] = $column;
        } else if (is_string($index) && $index == 'first') {
            array_unshift($this->columns, $column);
        } else {
            $this->columns[] = $column;
        }

        return $column;
    }

    /**
     * @return Column
     */
    public function addIdColumn()
    {

        $column = new IdColumn($this);

        return $this->addColumn($column, null);
    }

    /**
     * @return Column
     */
    public function addAuditColumn()
    {

        $column = new AuditColumn($this);

        return $this->addColumn($column, null);
    }

    /**
     * @return Column
     */
    public function addNoteColumn()
    {

        $column = new NoteColumn($this);

        return $this->addColumn($column, null);
    }

    /**
     * @return Column
     */
    public function addActionColumn()
    {

        $column = new ActionColumn($this);

        return $this->addColumn($column, null);
    }

    /**
     * @param string          $title
     * @param int|string|null $index
     *
     * @return Column
     */
    public function addTitleColumn($title, $index = null)
    {

        $column = new TitleColumn($this, $title);

        return $this->addColumn($column, $index);
    }

    /**
     * @param string          $title
     * @param int|string|null $index
     *
     * @return Column
     */
    public function addCountColumn($title, $index = null)
    {

        $column = new CountColumn($this, $title);

        return $this->addColumn($column, $index);
    }

    public function addDateColumn($title, $index = null)
    {

        $column = new DateColumn($this, $title);

        return $this->addColumn($column, $index);
    }

    public function addSelectColumn($index = null)
    {

        $column = new SelectColumn($this);

        return $this->addColumn($column, $index);
    }

    /**
     * @param int $index
     *
     * @return bool
     */
    public function has($index)
    {

        return array_key_exists($index, $this->columns);
    }

    /**
     * @param int $index
     *
     * @return Column
     * @throws \OutOfBoundsException
     */
    public function get($index)
    {

        if ($this->has($index)) {
            return $this->columns[$index];
        }
        throw new \OutOfBoundsException('Column index ' . $index . ' does not exist');
    }

    /**
     * @return array
     */
    public function getAll()
    {

        return $this->columns;
    }



    /*************************************************************************
     * "Action" specific functions
     *************************************************************************/

    /**
     * @param bool $refresh
     *
     * @return bool
     */
    public function hasSearchable($refresh = false)
    {

        if (array_key_exists('searchable', $this->flags) && !$refresh) {
            return $this->flags['searchable'];
        }

        $this->flags['searchable']          = false;
        $this->flaggedColumns['searchable'] = array();
        foreach ($this->getAll() as $column) {
            if ($column->isSearchable()) {
                $this->flags['searchable']            = true;
                $this->flaggedColumns['searchable'][] = $column;
            }
        }

        return $this->flags['searchable'];
    }

    /**
     * @param bool $refresh
     *
     * @return array
     */
    public function getSearchable($refresh = false)
    {

        $this->hasSearchable($refresh);

        return $this->flaggedColumns['searchable'];
    }

    /**
     * @param bool $refresh
     *
     * @return bool
     */
    public function hasSortable($refresh = false)
    {

        if (array_key_exists('sortable', $this->flags) && !$refresh) {
            return $this->flags['sortable'];
        }

        $this->flags['sortable']          = false;
        $this->flaggedColumns['sortable'] = array();
        foreach ($this->getAll() as $column) {
            if ($column->isSortable()) {
                $this->flags['sortable']            = true;
                $this->flaggedColumns['sortable'][] = $column;
            }
        }

        return $this->flags['sortable'];
    }

    /**
     * @param bool $refresh
     *
     * @return array
     */
    public function getSortable($refresh = false)
    {

        $this->hasSortable($refresh);

        return $this->flaggedColumns['sortable'];
    }

    /**
     * @param bool $refresh
     *
     * @return bool
     */
    public function hasFilterable($refresh = false)
    {

        if (array_key_exists('filterable', $this->flags) && !$refresh) {
            return $this->flags['filterable'];
        }

        $this->flags['filterable']          = false;
        $this->flaggedColumns['filterable'] = array();
        foreach ($this->getAll() as $column) {
            if ($column->isFilterable()) {
                $this->flags['filterable']            = true;
                $this->flaggedColumns['filterable'][] = $column;
            }
        }

        return $this->flags['filterable'];
    }

    /**
     * @param bool $refresh
     *
     * @return array
     */
    public function getFilterable($refresh = false)
    {

        $this->hasFilterable($refresh);

        return $this->flaggedColumns['filterable'];
    }

    /**
     * @param bool $refresh
     *
     * @return bool
     */
    public function hasHidden($refresh = false)
    {

        if (array_key_exists('hidden', $this->flags) && !$refresh) {
            return $this->flags['hidden'];
        }

        $this->flags['hidden']          = false;
        $this->flaggedColumns['hidden'] = array();
        foreach ($this->getAll() as $column) {
            if ($column->isHidden()) {
                $this->flags['hidden']            = true;
                $this->flaggedColumns['hidden'][] = $column;
            }
        }

        return $this->flags['hidden'];
    }

    /**
     * @param bool $refresh
     *
     * @return array
     */
    public function getHidden($refresh = false)
    {

        $this->hasHidden($refresh);

        return $this->flaggedColumns['hidden'];
    }
}