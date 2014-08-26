<?php

namespace Elektra\CrudBundle\Table\Column;

use Elektra\CrudBundle\Definition\Definition;
use Elektra\CrudBundle\Table\Columns;

/**
 * Class Column. Definitions for the single flags:
 *  - searchable: can be any column
 *  - sortable: can be any column. if the field is composite, a definition is required
 *  - filterable: only composite fields (definition required)
 *  - hidden: used for embedded tables -> TBD
 *
 * @package Elektra\CrudBundle\Table
 *
 * @version 0.1-dev
 */
// CHECK definition of hidden field and usage for embedded -> depends on the final definition of embedded flag in table
class Column
{

    /**
     * @var Columns
     */
    protected $columns;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var Definition
     */
    protected $definition;

    /**
     * @var array
     */
    protected $flags;

    /**
     * @var array
     */
    protected $fields;

    /**
     * @var string
     */
    protected $type;

    /**
     * @param Columns $columns
     * @param string  $title
     */
    public function __construct(Columns $columns, $title = '')
    {

        $this->columns    = $columns;
        $this->title      = $title;
        $this->type       = 'default';
        $this->definition = null;

        $this->flags  = array(
            'search' => false,
            'sort'   => false,
            'filter' => false,
            'hidden' => false,
        );
        $this->fields = array(
            'data'   => null,
            'search' => null,
            'sort'   => null,
            'filter' => null,
        );
    }

    /*************************************************************************
     * Data / Display related
     *************************************************************************/

    /**
     * @param mixed $entry the Entity object
     *
     * @return array|string
     */
    public function getDisplayData($entry)
    {

        // TODO / CHECK highlight search string?

        if (!$this->hasFieldData()) {
            // no data field -> nothing to display
            return '';
        }

        $field = $this->getFieldData();

        if (is_array($field)) {
            // multiple values to display
            $return = array();
            foreach ($field as $oneField) {
                $return[] = $this->getSingleDisplayValue($entry, $oneField);
            }
        } else if (is_string($field)) {
            // single value to display
            $return = $this->getSingleDisplayValue($entry, $field);
        }

        return $return;
    }

    /**
     * @param mixed  $entry the Entity object
     * @param string $field
     *
     * @return string
     */
    protected function getSingleDisplayValue($entry, $field)
    {

        if (strpos($field, '.') !== false) {
            // composite field -> loop trough associations
            $fields = explode('.', $field);
            $return = $entry;
            foreach ($fields as $oneField) {
                $method = 'get' . ucfirst($oneField);
                $return = $return->$method();
                if ($return === null) {
                    $return = '';
                    break;
                }
            }
        } else {
            // direct field
            $method = 'get' . ucfirst($field);
            $return = $entry->$method();
        }

        return $return;
    }

    /**
     * @param string $type
     *
     * @return Column
     */
    public function setType($type)
    {

        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {

        return $this->type;
    }



    /*************************************************************************
     * Common Getters / Setters
     *************************************************************************/

    /**
     * @return Columns
     */
    public function getColumns()
    {

        return $this->columns;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {

        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getTitle()
    {

        return $this->title;
    }

    /**
     * @param Definition $definition
     */
    public function setDefinition($definition)
    {

        $this->definition = $definition;
    }

    /**
     * @return Definition
     */
    public function getDefinition()
    {

        return $this->definition;
    }

    /*************************************************************************
     * Generic Getters / Setters
     *************************************************************************/

    /**
     * @param string $flag
     *
     * @return bool
     */
    public function hasFlag($flag)
    {

        return array_key_exists($flag, $this->flags);
    }

    /**
     * @param string $flag
     *
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function getFlag($flag)
    {

        if ($this->hasFlag($flag)) {
            return $this->flags[$flag];
        }

        throw new \InvalidArgumentException('Unknown flag "' . $flag . '"');
    }

    /**
     * @param string $flag
     * @param bool   $value
     *
     * @throws \InvalidArgumentException
     */
    public function setFlag($flag, $value)
    {

        if ($this->hasFlag($flag)) {
            $this->flags[$flag] = $value;
        } else {
            throw new \InvalidArgumentException('Unknown flag "' . $flag . '"');
        }
    }

    /**
     * @param string $field
     *
     * @return bool
     */
    public function hasField($field)
    {

        return array_key_exists($field, $this->fields);
    }

    /**
     * @param string $field
     *
     * @return bool
     */
    public function validField($field)
    {

        if ($this->hasField($field)) {
            if ($this->getField($field) !== null) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $field
     *
     * @return string|array
     * @throws \InvalidArgumentException
     */
    public function getField($field)
    {

        if ($this->hasField($field)) {
            return $this->fields[$field];
        }

        throw new \InvalidArgumentException('Unknown field "' . $field . '"');
    }

    /**
     * @param string       $field
     * @param string|array $value
     *
     * @throws \InvalidArgumentException
     */
    public function setField($field, $value)
    {

        if ($this->hasField($field)) {
            $this->fields[$field] = $value;
        } else {
            throw new \InvalidArgumentException('Unknown field "' . $field . '"');
        }
    }



    /*************************************************************************
     * Data field related
     *************************************************************************/

    /**
     * @return bool
     */
    public function hasFieldData()
    {

        return $this->validField('data');
    }

    /**
     * @param string|array $value
     *
     * @return Column
     */
    public function setFieldData($value)
    {

        $this->setField('data', $value);

        return $this;
    }

    /**
     * @return array|string
     */
    public function getFieldData()
    {

        return $this->getField('data');
    }

    /*************************************************************************
     * Search related
     *************************************************************************/

    /**
     * @return bool
     */
    public function hasFieldSearch()
    {

        $hasField = $this->validField('search');
        if ($hasField == false) {
            $hasField = $this->validField('data');
        }

        return $hasField;
    }

    /**
     * @param string|array $value
     *
     * @return Column
     */
    public function setFieldSearch($value)
    {

        $this->setField('search', $value);

        return $this;
    }

    /**
     * @return array|string
     */
    public function getFieldSearch()
    {

        $field = $this->getField('search');
        if ($field === null) {
            $field = $this->getField('data');
        }

        return $field;
    }

    /**
     * @return Column
     */
    public function setSearchable()
    {

        $this->setFlag('search', true);

        return $this;
    }

    /**
     * @return bool
     */
    public function isSearchable()
    {

        return $this->getFlag('search');
    }

    /*************************************************************************
     * Sort related
     *************************************************************************/

    /**
     * @return bool
     */
    public function hasFieldSort()
    {

        $hasField = $this->validField('sort');

        return $hasField;
    }

    /**
     * @param string $value
     *
     * @return Column
     */
    public function setFieldSort($value)
    {

        $this->setField('sort', $value);

        return $this;
    }

    /**
     * @return string
     */
    public function getFieldSort()
    {

        $field = $this->getField('sort');

        return $field;
    }

    /**
     * @return Column
     */
    public function setSortable()
    {

        $this->setFlag('sort', true);

        return $this;
    }

    /**
     * @return bool
     */
    public function isSortable()
    {

        return $this->getFlag('sort');
    }


    /*************************************************************************
     * Filter related
     *************************************************************************/

    /**
     * @return bool
     */
    public function hasFieldFilter()
    {

        $hasField = $this->validField('filter');

        return $hasField;
    }

    /**
     * @param string $value
     *
     * @return Column
     */
    public function setFieldFilter($value)
    {

        $this->setField('filter', $value);

        return $this;
    }

    /**
     * @return string
     */
    public function getFieldFilter()
    {

        $field = $this->getField('filter');

        return $field;
    }

    /**
     * @return Column
     */
    public function setFilterable()
    {

        $this->setFlag('filter', true);

        return $this;
    }

    /**
     * @return bool
     */
    public function isFilterable()
    {

        return $this->getFlag('filter');
    }

    /*************************************************************************
     * Hidden related
     *************************************************************************/

    /**
     * @return Column
     */
    public function setHidden()
    {

        $this->setFlag('hidden', true);

        return $this;
    }

    /**
     * @return bool
     */
    public function isHidden()
    {

        return $this->getFlag('hidden');
    }
}