<?php

namespace Elektra\CrudBundle\Table;

use Elektra\CrudBundle\Controller\Controller;
use Elektra\CrudBundle\Crud\Crud;
use Elektra\CrudBundle\Definition\Definition;
use Elektra\SeedBundle\Entity\EntityInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
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

    /**
     * @var Definition
     */
    protected $embedded;

    /*************************************************************************
     * Construction & Initialisation
     *************************************************************************/

    /**
     * @param Crud            $crud
     * @param Definition|null $embedded
     */
    public final function __construct(Crud $crud, $embedded = null)
    {

        $this->crud     = $crud;
        $this->embedded = $embedded;

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
        if ($this->crud->getDefinition()->isAuditable()) {
            $this->getColumns()->addAuditColumn();
        }
        if ($this->crud->getDefinition()->isAnnotable()) {
            $this->getColumns()->addNoteColumn();
        }
        if ($this->isAllowed('edit') || $this->isAllowed('delete')) {
            $this->getColumns()->addActionColumn();
        }

        $idColumn = $this->getColumns()->add('table.columns.id');
        $idColumn->setFieldData('id');
        $idColumn->setSortable();
        $idColumn->setType('id');

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
     * @return bool
     */
    public function isEmbedded()
    {

        return $this->embedded !== null;
    }

    /**
     * @return Definition|null
     */
    public function getEmbedded()
    {

        return $this->embedded;
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

        $request = $this->crud->getRequest();

        $this->requestData = array(
            'search'         => $this->crud->get('search', 'table', ''),
            'filters'        => $this->crud->get('filters', 'table', array()),
            'custom-filters' => $this->crud->get('custom-filters', 'table', array()),
            'order'          => $this->crud->get('order', 'table', array()),
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
            $this->crud->save($key, $value, 'table');
        }

        //                echo '<pre>After:\\n';
        //                var_dump($this->container->get('session')->all());
        //                echo '</pre>';
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

    //    public function getDefinition()
    //    {
    //
    //        return $this->crud->getDefinition();
    //    }

    //    /**
    //     * @return ContainerInterface
    //     */
    //    public function getContainer()
    //    {
    //
    //        return $this->container;
    //    }

    /*************************************************************************
     * Execution methods - Entry querying
     *************************************************************************/

    protected $relation;

    protected $relatedEntity;

    public function setRelation($relation, EntityInterface $entity)
    {

        $this->relation      = $relation;
        $this->relatedEntity = $entity;
    }

    public function load($page, $limit = 25)
    {

        $search  = null;
        $filters = null;
        $order   = null;

        if (isset($this->relatedEntity)) {
            $filters = $this->getLoadRelationFilter();
        } else {
            $search  = $this->getLoadSearch();
            $filters = $this->getLoadFilters();
            $order   = null;
        }

        $repositoryClass = $this->crud->getDefinition()->getClassRepository();
        $repository      = $this->crud->getController()->getDoctrine()->getRepository($repositoryClass);

        $this->entries = $repository->getEntries($page, $limit, $search, $filters, $order);
    }

    /**
     * @return array
     */
    public function getEntries()
    {

        return $this->entries;
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

        $template = $this->crud->getDefinition()->getPrefixView() . ':table.html.twig';

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

        $filters[$this->relation] = $this->relatedEntity->getId();

        return $filters;
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
        $builder = $this->crud->getService('form.factory')->createBuilder();
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
                $em       = $this->crud->getService('doctrine')->getManager();
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

        $options = array_merge_recursive($options, $defaultOptions);

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

        $builder = $this->crud->getService('form.factory')->createBuilder();
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

    public function getSpecificLangKey()
    {

        return $this->crud->getLangKey();
    }

    //    public function getAddLink()
    //    {
    //
    //        return $this->container->get('navigator')->getLink($this->definition, 'add');
    //    }
}