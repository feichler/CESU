<?php

namespace Elektra\CrudBundle\Table\Column;

use Elektra\CrudBundle\Crud\Definition;
use Elektra\CrudBundle\Table\Columns;
use Elektra\SiteBundle\Site\Helper;

class Column
{

    /**
     * back-reference to the containing Columns Object
     *
     * @var Columns
     */
    protected $columns;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $type;

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
     * Used for CSS
     *
     * @var string
     */
    protected $name;

    /**
     * @param Columns $columns
     * @param string  $title
     */
    public function __construct(Columns $columns, $title = '')
    {

        $this->columns    = $columns;
        $this->title      = Helper::languageAlternate('tables', 'columns.' . $title);
        $this->type       = 'default';
        $this->definition = null;

        $this->flags = array(
            'search' => false,
            'sort'   => false,
            'filter' => false,
            'hidden' => false,
        );

        $this->fields = array(
            'data'       => null,
            'dataIgnore' => null,
            'search'     => null,
            'sort'       => null,
            'filter'     => null,
        );
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {

        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {

        return $this->name;
    }

    /*************************************************************************
     * Common Getters / Setters
     *************************************************************************/

    /**
     * @return Columns
     */
    public final function getColumns()
    {

        return $this->columns;
    }

    /**
     * @param string $title
     */
    public final function setTitle($title)
    {

        $this->title = $title;
    }

    /**
     * @return string
     */
    public final function getTitle()
    {

        return $this->title;
    }

    /**
     * @param string $type
     *
     * @return Column
     */
    public final function setType($type)
    {

        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public final function getType()
    {

        return $this->type;
    }

    /**
     * @param Definition $definition
     */
    public final function setDefinition($definition)
    {

        $this->definition = $definition;

        return $this;
    }

    /**
     * @return Definition
     */
    public final function getDefinition()
    {

        return $this->definition;
    }

    /*************************************************************************
     * Internal Getters / Setters
     *************************************************************************/

    /**
     * @param string $flag
     *
     * @return bool
     */
    protected final function hasFlag($flag)
    {

        return array_key_exists($flag, $this->flags);
    }

    /**
     * @param string $flag
     *
     * @return bool
     * @throws \InvalidArgumentException
     */
    protected final function getFlag($flag)
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
    protected final function setFlag($flag, $value)
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
    protected final function hasField($field)
    {

        return array_key_exists($field, $this->fields);
    }

    /**
     * @param string $field
     *
     * @return bool
     */
    protected final function validField($field)
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
    protected final function getField($field)
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
    protected final function setField($field, $value)
    {

        if ($this->hasField($field)) {
            $this->fields[$field] = $value;
        } else {
            throw new \InvalidArgumentException('Unknown field "' . $field . '"');
        }
    }

    /*************************************************************************
     * Flag Getters / Setters
     *************************************************************************/

    /**
     * @return Column
     */
    public final function setSearchable()
    {

        $this->setFlag('search', true);

        return $this;
    }

    /**
     * @return bool
     */
    public final function isSearchable()
    {

        // easy check, search can use single or multiple data fields

        return $this->getFlag('search');
    }

    /**
     * @return Column
     */
    public final function setSortable()
    {

        $this->setFlag('sort', true);

        return $this;
    }

    /**
     * @return bool
     */
    public final function isSortable()
    {

        // more complex checking - sorting can only use single data field

        $flag = $this->getFlag('sort');
        if ($flag) {
            $field = $this->getField('sort');
            if (!empty($field) && is_string($field)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return Column
     */
    public final function setFilterable()
    {

        $this->setFlag('filter', true);

        return $this;
    }

    /**
     * @return bool
     */
    public final function isFilterable()
    {

        // more complex checking - filtering can only use single data field

        $flag = $this->getFlag('filter');
        if ($flag) {
            $field = $this->getField('filter');
            if (!empty($field) && is_string($field)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return Column
     */
    public final function setHidden()
    {

        $this->setFlag('hidden', true);

        return $this;
    }

    /**
     * @return bool
     */
    public final function isHidden()
    {

        return $this->getFlag('hidden');
    }

    /*************************************************************************
     * Data Field Getters / Setters
     *************************************************************************/

    /**
     * @return bool
     */
    public final function hasFieldData()
    {

        return $this->validField('data');
    }

    /**
     * @param string|array      $value
     * @param bool|string|array $ignoreNotFound
     *
     * @return Column
     */
    public final function setFieldData($value, $ignoreNotFound = false)
    {

        $this->setField('data', $value);

        $dataIgnore = array();

        if ($ignoreNotFound === false) {
            // dataIgnore left empty
        } else if ($ignoreNotFound === true) {
            if (is_string($value)) {
                $dataIgnore[$value] = true;
            } else if (is_array($value)) {
                foreach ($value as $field) {
                    $dataIgnore[$field] = true;
                }
            }
        } else if (is_string($ignoreNotFound)) {
            $dataIgnore[$ignoreNotFound] = true;
        } else if (is_array($ignoreNotFound)) {
            foreach ($ignoreNotFound as $field) {
                $dataIgnore[$field] = true;
            }
        }
        $this->setField('dataIgnore', $dataIgnore);

        return $this;
    }

    /**
     * @return array|string
     */
    public final function getFieldData()
    {

        return $this->getField('data');
    }

    /**
     * @return bool
     */
    public final function hasFieldSearch()
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
    public final function setFieldSearch($value)
    {

        $this->setField('search', $value);

        return $this;
    }

    /**
     * @return array|string
     */
    public final function getFieldSearch()
    {

        if ($this->hasFieldSearch()) {
            $field = $this->getField('search');
            if ($field === null) {
                // can use the default data field, even if there are multiple fields defined
                $field = $this->getField('data');
            }

            return $field;
        }

        return null;
    }

    /**
     * @return bool
     */
    public final function hasFieldSort()
    {

        $hasField = $this->validField('sort');
        if ($hasField == false) {
            $hasField = $this->validField('data');
        }

        return $hasField;
    }

    /**
     * @param string $value
     *
     * @return Column
     */
    public final function setFieldSort($value)
    {

        $this->setField('sort', $value);

        return $this;
    }

    /**
     * @return string
     */
    public final function getFieldSort()
    {

        if ($this->hasFieldSort()) {
            $field = $this->getField('sort');
            if ($field === null) {
                $field = $this->getField('data');
                if (is_array($field)) {
                    // if using the default data field, we need to check for multiple values and only use the first one
                    $field = $field[0];
                }
            }

            return $field;
        }

        return null;
    }

    /**
     * @return bool
     */
    public final function hasFieldFilter()
    {

        $hasField = $this->validField('filter');
        if ($hasField == false) {
            $hasField = $this->validField('data');
        }

        return $hasField;
    }

    /**
     * @param string $value
     *
     * @return Column
     */
    public final function setFieldFilter($value)
    {

        $this->setField('filter', $value);

        return $this;
    }

    /**
     * @return string
     */
    public final function getFieldFilter()
    {

        if ($this->hasFieldFilter()) {
            $field = $this->getField('filter');
            if ($field === null) {
                $field = $this->getField('data');
                if (is_array($field)) {
                    // if using the default data field, we need to check for multiple values and only use the first one
                    $field = $field[0];
                }
            }

            return $field;
        }

        return null;
    }

    /*************************************************************************
     * Display related
     *************************************************************************/

    /**
     * @param mixed $entry
     * @param int   $rowNumber
     *
     * @return string
     * @throws \RuntimeException
     */
    public function getDisplayData($entry, $rowNumber)
    {

        // CHECK highlight search string?

        if (!$this->hasFieldData()) {
            // no data field defined -> nothing to display
            return '';
        }

        $field      = $this->getFieldData();
        $dataIgnore = $this->getField('dataIgnore');

        if (is_array($field)) {
            // multiple values to display
            $return = array();
            foreach ($field as $oneField) {
                try {
                    $data     = $this->getDisplayDataSingle($entry, $oneField, $rowNumber);
                    $return[] = $data;
                } catch (\RuntimeException $ex) {
                    if (!array_key_exists($oneField, $dataIgnore)) {
                        throw $ex;
                    }
                }
            }
        } else if (is_string($field)) {
            // only one value to display
            try {
                $return = $this->getDisplayDataSingle($entry, $field, $rowNumber);
            } catch (\RuntimeException $ex) {
                if (!array_key_exists($field, $dataIgnore)) {
                    throw $ex;
                } else {
                    $return = '';
                }
            }
        } else {
            throw new \RuntimeException('invalid data field type');
        }

        return $return;
    }

    /**
     * @param mixed  $entry
     * @param string $field
     * @param int    $rowNumber
     *
     * @return null|string
     * @throws \RuntimeException
     */
    protected final function getDisplayDataSingle($entry, $field, $rowNumber)
    {

        // check if a custom method is present
        $custom = $this->getCustomDisplayDataSingle($entry, $field, $rowNumber);
        if ($custom !== null) {
            return $custom;
        }

        //        $dataIgnore = $this->getField('dataIgnore');

        if (strpos($field, '.') !== false) {
            // composite field -> loop through the parts
            $fields = explode('.', $field);
            $return = $entry;
            foreach ($fields as $oneField) {
                $method = 'get' . ucfirst($oneField);
                if (!method_exists($return, $method)) {
                    throw new \RuntimeException('field "' . $field . '" is not accessible at ' . get_class($entry));
                }
                $return = $return->$method();
                if ($return === null) {
                    $return = '';
                    break;
                }
            }
        } else {
            // direct accessible field
            $method = 'get' . ucfirst($field);
            if (!method_exists($entry, $method)) {
                throw new \RuntimeException('field "' . $field . '" is not accessible at ' . get_class($entry));
            }
            $return = $entry->$method();
        }

        return $return;
    }

    /**
     * @param mixed  $entry
     * @param string $field
     * @param int    $rowNumber
     *
     * @return null|string
     */
    protected function getCustomDisplayDataSingle($entry, $field, $rowNumber)
    {

        // NOTE may be overridden if necessary
        return null;
    }
}