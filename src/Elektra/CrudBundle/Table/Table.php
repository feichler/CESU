<?php

namespace Elektra\CrudBundle\Table;

use Elektra\CrudBundle\Crud\Crud;

use Elektra\CrudBundle\Crud\Definition;
use Symfony\Component\Form\FormBuilder;

// TODO add a "clear all filters" button

abstract class Table
{

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

    /**
     * @var array
     */
    protected $requestData;

    /**
     * @var array
     */
    protected $customFilters;

    /*************************************************************************
     * Construction & Initialisation
     *************************************************************************/

    /**
     * @param Crud $crud
     */
    public final function __construct(Crud $crud)
    {

        $this->crud = $crud;

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
     * @return Crud
     */
    public function getCrud()
    {

        return $this->crud;
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

    public function getRequestData($type, $id = null)
    {

        if ($id === null) {
            return $this->requestData[$type];
        } else {
            return $this->requestData[$type][$id];
        }
    }

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

    protected final function addCustomFilter($name, $type, $options)
    {

        $filter = array(
            'name'    => $name,
            'type'    => $type,
            'options' => $options,
        );

        $this->customFilters[] = $filter;
    }

    /*************************************************************************
     * Execution methods - Entry querying
     *************************************************************************/

    //    protected $relation;
    //
    //    protected $relatedEntity;
    //
    //    public function setRelation($relation, EntityInterface $entity)
    //    {
    //
    //        $this->relation      = $relation;
    //        $this->relatedEntity = $entity;
    //    }

    public function load($page)
    {

        $search  = null;
        $filters = null;
        $order   = null;

        // URGENT check the embedded functionality
        if ($this->getCrud()->hasParent()) {
            $filters = $this->getLoadRelationFilter();
        } else {
            $search  = $this->getLoadSearch();
            $filters = $this->getLoadFilters();
            $order   = $this->getLoadOrder();
        }

        $repositoryClass = $this->getCrud()->getDefinition()->getClassRepository();
        $repository      = $this->getCrud()->getController()->getDoctrine()->getRepository($repositoryClass);

        $this->entries = $repository->getEntries($page, $this->pagination->getLimit(), $search, $filters, $order);

        $this->pagination->setPage($page);
        $language = $this->getCrud()->getService('siteLanguage');
        if ($language instanceof Language) {
            $language->add('pagination.pages', 'common.pagination.pages', array('page' => $page, 'max' => $this->pagination->getMaxPage()));
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
     * @return int
     */
    public function getEntryCount()
    {

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

    public function hasFilters()
    {

        // first check the generic filters
        $return = $this->columns->hasFilterable();

        if ($return == false) {
            // check custom filters
            $return = count($this->customFilters) != 0;
        }

        return $return;
    }

    private function getLoadRelationFilter()
    {

        $filters = array();
        //        var_dump($this->getCrud()->isEmbedded());
        //        echo 'TESTING:<br/>';
        //        $def = $this->getCrud()->getDefinition();
        //        echo $def->getName();

        $filters[$this->getRelationFilterName($this->getCrud()->getParentDefinition())] = $this->getCrud()->getParentEntity()->getId();

        return $filters;
    }

    protected function getRelationFilterName(Definition $parentDefinition)
    {

        // NOTE override if the implemented behaviour should differ

        if($this->getCrud()->getParentRelationName() !== null) {
            return $this->getCrud()->getParentRelationName();
        }

        return strtolower($parentDefinition->getName());
    }

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

    protected function getCustomLoadFilter($options)
    {

        throw new \RuntimeException('Method not implemented by specific table class');
    }

    public function getFilters()
    {

        // create an array of ready-to-use form view elements for filtering
        $builder = $this->getCrud()->getService('form.factory')->createBuilder();
        $filters = array();

        // first, create the custom filters (type-specific)
        foreach ($this->customFilters as $filter) {
            $filterName = $this->getFilterFieldName($filter);
            $selected   = $this->getRequestData('custom-filters', $filterName);

            $filterField = $builder->create(
                $filterName,
                $filter['type'],
                $this->prepareFilterOptions($filter['options'], $selected)
            )->getForm()->createView();

            $filters[] = $filterField;
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
                        'empty_value' => 'Please Select ??', // TRANSLATE empty option
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

    private function prepareFilterOptions($options, $data = null)
    {

        $defaultOptions = array(
            'label'    => false,
            'required' => false,
            'attr'     => array(
                'onchange' => 'jQuery(this).closest(\'form\').trigger(\'submit\');',
            ),
        );

        $options = $this->getCrud()->mergeOptions($defaultOptions, $options);

        if ($data != null && !empty($data)) {
            $options['data'] = $data;
        }

        return $options;
    }

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

    public function hasSearch()
    {

        // check the columns
        $return = $this->columns->hasSearchable();

        return $return;
    }

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

    public function getSearchString()
    {

        return $this->requestData['search'];
    }

    /*************************************************************************
     * Ordering related methods
     *************************************************************************/

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
}