<?php

namespace Elektra\CrudBundle\Table;

use Elektra\CrudBundle\Crud\Crud;
use Elektra\CrudBundle\Crud\Definition;
use Elektra\SiteBundle\Site\Helper;
use Elektra\SiteBundle\Site\Language;
use Symfony\Component\Form\FormBuilder;

// TODO add a "clear all filters" button

abstract class Table
{

    /**
     * @var string
     */
    protected $id;

    /**
     * @var Crud
     */
    protected $crud;

    /**
     * @var Pagination
     */
    protected $pagination;

    /**
     * @var Columns
     */
    protected $columns;

    /**
     * @var array
     */
    protected $actions;

    /**
     * @var array
     */
    protected $entries;

    protected $entriesSet = false;

    /**
     * @var array
     */
    protected $requestData;

    /**
     * @var array
     */
    protected $customFilters;

    /**
     * @var bool
     */
    protected $inView;

    /**
     * @var
     */
    protected $options;

    /*************************************************************************
     * Construction & Initialisation
     *************************************************************************/

    /**
     * @param Crud $crud
     */
    public final function __construct(Crud $crud)
    {

        $this->crud = $crud;
        Helper::setCrud($this->crud);

        $this->id            = $this->getCrud()->getDefinition()->getKey();
        $this->pagination    = new Pagination($this);
        $this->columns       = new Columns($this);
        $this->customFilters = array();
        $this->entries       = array();
        // NOTE $this->actions defines the allowed actions
        $this->actions = array(
            'add'    => true,
            'view'   => true,
            'edit'   => true,
            'delete' => true,
        );
        $this->inView  = false;
        $this->options = array();

        // call the specific initialisation methods for this specific table
        $this->initialiseColumns();
        $this->initialiseCustomFilters();
        $this->initialiseActions();

        // depending on the type of entry, add some generic columns
        if ($this->getCrud()->getDefinition()->isEntityAuditable()) {
            $this->getColumns()->addAuditColumn();
        }
        if ($this->getCrud()->getDefinition()->isEntityAnnotable()) {
            $this->getColumns()->addNoteColumn();
        }
        if ($this->isAllowed('edit') || $this->isAllowed('delete')) {
            $this->getColumns()->addActionColumn();
        }
        $this->getColumns()->addIdColumn();

        $this->initialiseRequestData();
    }

    /**
     * @param mixed $options
     */
    public function setOptions($options)
    {

        $this->options = $options;
    }

    /**
     * @return mixed
     */
    public function getOptions()
    {

        return $this->options;
    }

    /**
     * @return Crud
     */
    public function getCrud()
    {

        return $this->crud;
    }

    /**
     * @return string
     */
    public function getId()
    {

        return $this->id;
    }

    /**
     *
     */
    protected abstract function initialiseColumns();

    /**
     *
     */
    protected function initialiseCustomFilters()
    {
        // Override if required
    }

    /**
     *
     */
    protected function initialiseActions()
    {
        // Override if required
    }

    /**
     *
     */
    protected final function initialiseRequestData()
    {

        $request = $this->getCrud()->getRequest();

        $this->requestData = array(
            'search'         => $this->getCrud()->getData('search', 'table', ''),
            'filters'        => $this->getCrud()->getData('filters', 'table', array()),
            'custom-filters' => $this->getCrud()->getData('custom-filters', 'table', array()),
            'order'          => $this->getCrud()->getData('order', 'table', array()),
        );

        if ($this->columns->hasSearchable()) {
            // find and store the search value
            $fieldName  = $this->getFilterFieldName('search');
            $fieldValue = $request->get($fieldName, null);
            if (empty($fieldValue)) {
                $fieldValue = '';
            }
            $this->setRequestData('search', $fieldValue);
        }

        if ($this->columns->hasFilterable()) {
            // find and store the filter values
            foreach ($this->getColumns()->getFilterable() as $column) {
                $fieldName  = $this->getFilterFieldName($column->getDefinition());
                $fieldValue = $request->get($fieldName, null);
                if (empty($fieldValue)) {
                    $fieldValue = '';
                }
                $this->setRequestData('filters', $fieldValue, $fieldName);
            }
        }

        if (!empty($this->customFilters)) {
            // find and store the custom filters
            foreach ($this->customFilters as $customFilter) {
                $fieldName  = $this->getFilterFieldName($customFilter);
                $fieldValue = $request->get($fieldName, null);
                if (empty($fieldValue)) {
                    $fieldValue = '';
                }
                $this->setRequestData('custom-filters', $fieldValue, $fieldName);
            }
        }

        if ($this->columns->hasSortable()) {
            // find and store the ordering
            // TODO implement
        }

        foreach ($this->requestData as $key => $value) {
            $this->getCrud()->setData($key, $value, 'table');
        }
    }

    /**
     * @param      $type
     * @param null $id
     *
     * @return mixed
     */
    public function getRequestData($type, $id = null)
    {

        if ($id === null) {
            return $this->requestData[$type];
        } else {
            return $this->requestData[$type][$id];
        }
    }

    /**
     * @param      $type
     * @param      $value
     * @param null $id
     */
    public function setRequestData($type, $value, $id = null)
    {

        if ($id === null) {
            $this->requestData[$type] = $value;
        } else {
            $this->requestData[$type][$id] = $value;
        }
    }

    /*************************************************************************
     * Definition methods
     *************************************************************************/

    /**
     * @param $name
     * @param $type
     * @param $options
     * @param $visible
     */
    protected final function addCustomFilter($name, $type, $options, $visible = true)
    {

        $filter = array(
            'name'    => $name,
            'type'    => $type,
            'options' => $options,
            'visible' => $visible,
        );

        $this->customFilters[] = $filter;
    }

    /*************************************************************************
     * Execution methods - Entry querying
     *************************************************************************/

    /**
     * @param $page
     */
    public function load($page)
    {

        $search  = null;
        $filters = null;
        $order   = null;

        // URGENT check the embedded functionality
        if ($this->getCrud()->hasParent()) {
            $filters = $this->getLoadRelationFilter();
            //            var_dump($this->options);
            if (isset($this->options['orderingField']) && $this->options['orderingField'] != '') {
                $order = array(
                    $this->options['orderingField'] => $this->options['orderingDirection']
                );
            }
            //            var_dump($order);
        } else {
            $search  = $this->getLoadSearch();
            $filters = $this->getLoadFilters();
            $order   = $this->getLoadOrder();
        }

        if (isset($this->options['listLimit']) && $this->options['listLimit'] != '') {
            $this->pagination->setLimit($this->options['listLimit']);
        }

        $repositoryClass = $this->getCrud()->getDefinition()->getClassRepository();
        $repository      = $this->getCrud()->getController()->getDoctrine()->getRepository($repositoryClass);
        //        echo get_class($repository);
        $this->entries = $repository->getEntries($page, $this->pagination->getLimit(), $search, $filters, $order);
        //        echo count($this->entries);
        $this->pagination->setPage($page);
        $language = $this->getCrud()->getService('siteLanguage');
        if ($language instanceof Language) {
            $language->add('pagination.pages', 'tables.generic.pagination.pages', array('page' => $page, 'max' => $this->pagination->getMaxPage()));
        }
    }

    /**
     * @return array
     */
    public function getEntries()
    {

        return $this->entries;
    }

    /**
     * @param $entries
     *
     * @throws \RuntimeException
     */
    public function setEntries($entries)
    {

        if (is_array($entries) || $entries instanceof \Traversable) {
            foreach ($entries as $entry) {
                if (method_exists($entry, 'getId')) {
                    //                    echo 'b';
                    $this->entries[$entry->getId()] = $entry;
                } else {
                    $this->entries[] = $entry;
                }
            }
            $this->entriesSet = true;
        } else {
            throw new \RuntimeException('Given entries are not traversable');
        }
    }

    /**
     * @return int
     */
    public function getEntryCount()
    {

        if ($this->entriesSet) {
            return count($this->entries);
        }

        $search  = null;
        $filters = null;
        $order   = null;

        // URGENT check the embedded functionality
        if ($this->getCrud()->hasParent()) {
            $filters = $this->getLoadRelationFilter();
        } else {
            $search  = $this->getLoadSearch();
            $filters = $this->getLoadFilters();
            $order   = null;
        }

        $repositoryClass = $this->getCrud()->getDefinition()->getClassRepository();
        $repository      = $this->getCrud()->getController()->getDoctrine()->getRepository($repositoryClass);

        $entryCount = $repository->getCount($search, $filters, $order);

        return $entryCount;
    }

    /*************************************************************************
     * Column related methods
     *************************************************************************/

    public function setSelectable()
    {

        $this->getColumns()->addSelectColumn('first');
    }

    /**
     * @return Columns
     */
    public function getColumns()
    {

        return $this->columns;
    }

    /*************************************************************************
     * Rendering related methods
     *************************************************************************/

    /**
     * @return string
     */
    public function getTemplate()
    {

        $template = $this->getCrud()->getDefinition()->getView() . ':table.html.twig';

        return $template;
    }

    /*************************************************************************
     * Action related methods
     *************************************************************************/

    /**
     * Only generates the exception
     *
     * @param string $action
     *
     * @throws \InvalidArgumentException
     */
    private function unknownAction($action)
    {

        throw new \InvalidArgumentException('Unknown Action "' . $action . '"');
    }

    /**
     * @param string $action
     *
     * @return bool
     */
    public function hasAction($action)
    {

        return array_key_exists($action, $this->actions);
    }

    /**
     * @param string $action
     *
     * @throws \InvalidArgumentException
     */
    public function allowAction($action)
    {

        if ($this->hasAction($action)) {
            $this->actions[$action] = true;

            return;
        }

        $this->unknownAction($action);
    }

    /**
     * @param string $action
     *
     * @throws \InvalidArgumentException
     */
    public function disallowAction($action)
    {

        if ($this->hasAction($action)) {
            $this->actions[$action] = false;

            return;
        }

        $this->unknownAction($action);
    }

    /**
     * @param string $action
     *
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function isAllowed($action)
    {

        if ($this->hasAction($action)) {
            return $this->actions[$action];
        }

        $this->unknownAction($action);
    }

    /*************************************************************************
     * Filter related methods
     *************************************************************************/

    /**
     * @return bool
     */
    public function hasFilters()
    {

        // first check the generic filters
        $return = $this->columns->hasFilterable();

        if ($return == false) {
            foreach ($this->customFilters as $filter) {
                if ($filter['visible'] == true) {
                    $return = true;
                    break;
                }
            }
            // check custom filters
            //            $return = count($this->customFilters) != 0;
        }

        return $return;
    }

    /**
     * @return array
     */
    private function getLoadRelationFilter()
    {

        $filters = array();

        $filters[$this->getRelationFilterName($this->getCrud()->getParentDefinition())] = $this->getCrud()->getParentEntity()->getId();

        return $filters;
    }

    /**
     * @param Definition $parentDefinition
     *
     * @return string
     */
    protected function getRelationFilterName(Definition $parentDefinition)
    {

        // NOTE override if the implemented behaviour should differ

        if ($this->getCrud()->getParentRelationName() !== null) {
            return $this->getCrud()->getParentRelationName();
        }

        return lcfirst($parentDefinition->getName());
    }

    /**
     * @return array
     */
    private function getLoadFilters()
    {

        $filters = array();

        if ($this->hasFilters()) {
            // first prepare the generic filters
            foreach ($this->columns->getFilterable() as $filterColumn) {
                $name       = lcfirst($filterColumn->getDefinition()->getName());
                $filterName = $this->getFilterFieldName($filterColumn->getDefinition());
                $value      = $this->getRequestData('filters', $filterName);
                if ($value != null && !empty($value)) {
                    $filters[$name] = $value;
                }
            }

            // second prepare the custom filters
            foreach ($this->customFilters as $filter) {
                $filters = array_merge($filters, $this->getCustomLoadFilter($filter));
            }
        }

        return $filters;
    }

    /**
     * @param $options
     *
     * @throws \RuntimeException
     */
    protected function getCustomLoadFilter($options)
    {

        throw new \RuntimeException('Method not implemented by specific table class');
    }

    /**
     * @return array
     */
    public function getFilters()
    {

        // create an array of ready-to-use form view elements for filtering
        $builder = $this->getCrud()->getService('form.factory')->createBuilder();
        $filters = array();

        // first, create the custom filters (type-specific)
        foreach ($this->customFilters as $filter) {
            if ($filter['visible']) {
                $filterName = $this->getFilterFieldName($filter);
                $selected   = $this->getRequestData('custom-filters', $filterName);

                $filterField = $builder->create(
                    $filterName,
                    $filter['type'],
                    $this->prepareFilterOptions($filter['options'], $selected)
                )->getForm()->createView();

                $filters[] = $filterField;
            }
        }

        // second, create the column-related filters
        foreach ($this->columns->getFilterable() as $filterColumn) {
            $definition = $filterColumn->getDefinition();
            $filterName = $this->getFilterFieldName($definition);
            $selected   = $this->getRequestData('filters', $filterName);

            // prepare the selected option
            if ($selected != '') {
                $em       = $this->getCrud()->getService('doctrine')->getManager();
                $selected = $em->getReference($definition->getClassEntity(), $selected);
            }

            $filterField = $builder->create(
                $filterName,
                'entity',
                $this->prepareFilterOptions(
                    array(
                        'empty_value' => Helper::languageAlternate('tables', 'filters.' . $definition->getName()),
                        'class'       => $definition->getClassEntity(),
                        'property'    => $filterColumn->getFieldFilter(),
                    ),
                    $selected
                )
            )->getForm()->createView();

            $filters[] = $filterField;
        }

        return $filters;
    }

    /**
     * @param      $options
     * @param null $data
     *
     * @return array
     */
    private function prepareFilterOptions($options, $data = null)
    {

        $defaultOptions = array(

            'label'    => false,
            'required' => false,
            'attr'     => array(
                'name'     => 'filter-submit',
                'onchange' => 'jQuery(this).closest(\'form\').find(\'[name="filter-submit"]\').trigger(\'click\');',
            ),
        );

        $options = Helper::mergeOptions($defaultOptions, $options);
        //        $options = $this->getCrud()->mergeOptions($defaultOptions, $options);

        if ($data != null && !empty($data)) {
            $options['data'] = $data;
        }

        return $options;
    }

    /**
     * @param $options
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    protected function getFilterFieldName($options)
    {

        if ($options instanceof Definition) {
            $name = 'table-filter-' . lcfirst($options->getName());
        } else if (is_array($options)) {
            $name = 'table-customfilter-' . $options['name'];
        } else if (is_string($options) && $options == 'search') {
            $name = 'table-search';
        } else {
            throw new \InvalidArgumentException('Cannot process options for filter field name');
        }

        return $name;
    }

    /*************************************************************************
     * Search related methods
     *************************************************************************/

    /**
     * @return bool
     */
    public function hasSearch()
    {

        // check the columns
        $return = $this->columns->hasSearchable();

        return $return;
    }

    /**
     * @return \Symfony\Component\Form\FormView
     */
    public function getSearchField()
    {

        $builder = $this->getCrud()->getService('form.factory')->createBuilder();
        if ($builder instanceof FormBuilder) {
            $field = $builder->create(
                $this->getFilterFieldName('search'),
                'search',
                array(
                    'required' => false,
                    'data'     => $this->getRequestData('search'),
                    'attr'     => array(
                        'placeholder' => 'Search', // TRANSLATE OPTION
                    ),
                )
            );

            return $field->getForm()->createView();
        }
    }

    /**
     * @return array|null
     */
    private function getLoadSearch()
    {

        $return = null;

        if ($this->hasSearch()) {
            $string = $this->requestData['search'];
            if ($string != '') {
                // only add if a search string is given
                $fields = array();
                foreach ($this->columns->getSearchable() as $column) {
                    $searchFields = $column->getFieldSearch();
                    if (is_array($searchFields)) {
                        $fields = array_merge($fields, $searchFields);
                    } else {
                        $fields[] = $searchFields;
                    }
                }

                if (!empty($fields)) {
                    $return = array(
                        'value'  => $string,
                        'fields' => $fields,
                    );
                }
            }
        }

        return $return;
    }

    /**
     * @return mixed
     */
    public function getSearchString()
    {

        return $this->requestData['search'];
    }

    /*************************************************************************
     * Ordering related methods
     *************************************************************************/

    /**
     * @return array
     */
    private function getLoadOrder()
    {

        // TODO implement

        return array();
    }

    /**
     * @return Pagination
     */
    public function getPagination()
    {

        return $this->pagination;
    }

    /**
     * @param boolean $inView
     */
    public function setInView($inView)
    {

        $this->inView = $inView;
    }

    /**
     * @return boolean
     */
    public function getInView()
    {

        return $this->inView;
    }
}