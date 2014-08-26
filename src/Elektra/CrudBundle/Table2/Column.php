<?php

namespace Elektra\CrudBundle\Table;

use Elektra\CrudBundle\Definition\Definition;

class Column2
{

    /**
     * @var string
     */
    protected $title;

    /**
     * @var mixed string or array
     */
    protected $dataField;

    /**
     * @var string
     */
    protected $searchField;

    /**
     * @var bool
     */
    protected $sortable;

    /**
     * @var bool
     */
    protected $searchable;

    /**
     * @var bool
     */
    protected $filterable;

    /**
     * @var bool
     */
    protected $hidden;

    /**
     * @var string
     */
    protected $width;

    public function __construct($title = '')
    {

        $this->title      = $title;
        $this->dataField  = '';
        $this->sortable   = false;
        $this->searchable = false;
        $this->filterable = false;
        $this->hidden     = false;
        $this->width      = '';
    }

    //    /**
    //     * @param string $title
    //     *
    //     * @return Column (for chaining)
    //     */
    //    public function setTitle($title)
    //    {
    //
    //        $this->title = $title;
    //
    //        return $this;
    //    }
    //
    //    /**
    //     * @return string
    //     */
    //    public function getTitle()
    //    {
    //
    //        return $this->title;
    //    }

    //    /**
    //     * @param string $dataField
    //     *
    //     * @return Column (for chaining)
    //     */
    //    public function setDataField($dataField)
    //    {
    //
    //        $this->dataField = $dataField;
    //
    //        return $this;
    //    }
    //
    //    /**
    //     * @return string
    //     */
    //    public function getDataField()
    //    {
    //
    //        return $this->dataField;
    //    }

    /**
     * @return bool
     */
    public function hasDataField()
    {

        return (!empty($this->dataField) && $this->dataField != '');
    }

    //    /**
    //     *
    //     * @return Column (for chaining)
    //     */
    //    public function setSortable()
    //    {
    //
    //        $this->sortable = true;
    //
    //        return $this;
    //    }
    //
    //    /**
    //     * @return bool
    //     */
    //    public function isSortable()
    //    {
    //
    //        return ($this->sortable && $this->hasDataField() && !$this->isEmpty());
    //    }

    //    /**
    //     *
    //     * @return Column (for chaining)
    //     */
    //    public function setSearchable()
    //    {
    //
    //        $this->searchable = true;
    //
    //        return $this;
    //    }
    //
    //    /**
    //     * @return bool
    //     */
    //    public function isSearchable()
    //    {
    //
    //        return ($this->searchable && $this->hasDataField());
    //    }
    //
    //    /**
    //     * @return Column (for chaining)
    //     */
    //    public function setFilterable()
    //    {
    //
    //        $this->filterable = true;
    //
    //        return $this;
    //    }
    //
    //    /**
    //     * @return boolean
    //     */
    //    public function isFilterable()
    //    {
    //
    //        return $this->filterable;
    //    }

    //    /**
    //     * @return Column (for chaining)
    //     */
    //    public function setHidden()
    //    {
    //
    //        $this->hidden = true;
    //
    //        return $this;
    //    }
    //
    //    /**
    //     * @return boolean
    //     */
    //    public function isHidden()
    //    {
    //
    //        return $this->hidden;
    //    }

    /**
     * @return mixed
     */
    public function isEmpty()
    {

        return (empty($this->title) || $this->title == '');
    }

    /**
     * @param string $width
     *
     * @return Column (for chaining)
     */
    public function setWidth($width)
    {

        $this->width = $width;

        return $this;
    }

    /**
     * @return string
     */
    public function getWidth()
    {

        return $this->width;
    }

    public function getData($entry)
    {

        if (!$this->hasDataField()) {
            // empty column - no data field defined
            return '';
        }

        $field = $this->getDataField();

        if (is_array($field)) {
            $return = array();
            foreach ($field as $oneField) {
                $return[] = $this->getFieldValue($entry, $field);
            }
        } else if (is_string($field)) {

            return $this->getFieldValue($entry, $field);
        }

        // fallback return
        return '';

        if ($this->hasDataField()) {
            $dataField = $this->getDataField();

            if (is_string($dataField)) {
                $method = 'get' . ucfirst($dataField);

                return $entry->$method();
            } else if (is_array($dataField)) {
                $return = array();
                foreach ($dataField as $key => $value) {
                    if (!is_numeric($key)) {
                    } else {
                        $method   = 'get' . ucfirst($value);
                        $return[] = $entry->$method();
                    }
                }

                return $return;
            }
        }

        return '';

        if ($this->hasDataField()) {
            $method = 'get' . ucfirst($this->dataField);

            return $entry->$method();
        }

        return '';
    }

    private function getFieldValue($entry, $field)
    {

        if (strpos($field, '.') !== false) {
            // need to ask references
            $fields = explode('.', $field);
            $return = $entry;
            foreach ($fields as $oneField) {
                $method = 'get' . ucfirst($oneField);
                $return = $return->$method();
            }

            return $return;
        } else {
            $method = 'get' . ucfirst($field);

            return $entry->$method();
        }
    }

    //    /**
    //     * @param string $searchField
    //     *
    //     * @return Column (for chaining)
    //     */
    //    public function setSearchField($searchField)
    //    {
    //
    //        $this->searchField = $searchField;
    //
    //        return $this;
    //    }
    //
    //    /**
    //     * @return string
    //     */
    //    public function getSearchField()
    //    {
    //
    //        return $this->searchField;
    //    }
}