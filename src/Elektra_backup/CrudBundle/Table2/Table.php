<?php

namespace Elektra\CrudBundle\Table;

use Doctrine\Common\Collections\Criteria;
use Elektra\CrudBundle\Definition\Definition;
use Elektra\CrudBundle\Navigator\Navigator;
use Elektra\CrudBundle\Table\Column\Action;
use Elektra\CrudBundle\Table\Column\Audit;
use Elektra\CrudBundle\Table\Column\Id;
use Elektra\CrudBundle\Table\Column\Note;
use Elektra\SeedBundle\Entity\SeedUnits\Model;
use MyProject\Proxies\__CG__\OtherProject\Proxies\__CG__\stdClass;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class Table2
{

    //    /**
    //     * @var Navigator
    //     */
    //    protected $navigator;
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var Definition
     */
    protected $definition;

    /**
     * @var array
     */
    protected $columns;

    /**
     * @var array
     */
    protected $actions;

    /**
     * @var bool
     */
    protected $embedded;

    /**
     * @var array
     */
    protected $entries;

    /**
     * @var array
     */
    protected $filters;

    public function __construct(ContainerInterface $container, Definition $definition, $embedded = false)
    {

        //        parent::__construct();

//        $this->container  = $container;
//        $this->definition = $definition;
        $this->embedded   = $embedded;

        $search = $this->container->get('request')->get('search', '');
        $pc     = $this->container->get('request')->get('powerCordType', '');

        echo 'S: ' . $search . '<br />';
        echo 'PC: ' . $pc . '<br />';
        $this->columns = array();
        $this->filters = array();

        // NOTE: array - action => allowed flag
        $this->actions = array(
            'view'   => true,
            'add'    => true,
            'edit'   => true,
            'delete' => true,
        );

        $this->initialise();

        if ($this->definition->isAuditable()) {
            $this->addAuditColumn();
        }
        if ($this->definition->isAnnotable()) {
            $this->addNoteColumn();
        }
        if ($this->isAllowed('edit') || $this->isAllowed('delete')) {
            $this->addActionColumn();
        }
        $this->addIdColumn();
    }

    private function getExecuteSearch()
    {

        $searchString = null;
        if ($this->hasSearch()) {
            $searchString = $this->container->get('request')->get('search', null);
        }

        if ($searchString !== null) {
            $search         = new stdClass();
            $search->string = $searchString;
            $search->fields = array();
            foreach ($this->columns as $column) {
                if ($column->isSearchable()) {
                    $search->fields[] = $column->getSearchField();
                }
            }

            return $search;
        }

        return null;
    }

    private function getExecuteFilters() {

    }

    public function execute()
    {

        $search = $this->getExecuteSearch();
        $filters = $this->getExecuteFilters();

        $search = null;
        if ($this->hasSearch()) {
            $search = $this->container->get('request')->get('search', null);
        }
        $filters = array();
        if ($this->hasFilters()) {
            foreach ($this->filters as $oneFilter) {
                $name           = lcfirst($oneFilter->getName());
                $value          = $this->container->get('request')->get($name, '');
                $filters[$name] = $value;
            }
        }

        $searchFields  = array();
        $searchString  = null;
        $filterArray   = array();
        $orderingArray = array();

        if ($search != null) {
            $searchString = $search;
            foreach ($this->columns as $column) {
                if ($column->isSearchable()) {
                    $searchFields[] = $column->getSearchField();
                }
            }
        }

        $repositoryClass = $this->definition->getClassRepository();
        $repository      = $this->container->get('doctrine')->getRepository($repositoryClass);
        $entries         = $repository->getEntries(1, 25, $searchFields, $searchString, $filterArray, $orderingArray);

        return;

        $searchArray   = array();
        $filtersArray  = array();
        $orderingArray = array();

        $repFilters = array();
        if ($search != null) {
            foreach ($this->columns as $column) {
                if ($column->isSearchable()) {

                    //$repFilters = Criteria::expr()->contains($column->getSearchField(), $search);
                    $repFilters[$column->getSearchField()] = '%' . $search . '%';
                }
            }
        }

        var_dump($repFilters);
        echo '<br />';

        $repositoryClass = $this->definition->getClassRepository();
        $repository      = $this->container->get('doctrine')->getRepository($repositoryClass);
        $entries         = $repository->getEntries(1, 25, $repFilters);
        echo '<br /><br />';
        echo count($entries) . '<br />';

        echo 'Search: ';
        var_dump($search);
        echo '<br />';
        echo 'Filters: ';
        var_dump($filters);
        echo '<br />';
    }

    /**
     *
     */
    protected abstract function initialise();

//    public function getTemplate()
//    {
//
//        $template = $this->definition->getPrefixView() . ':table.html.twig';
//
//        return $template;
//
//        //        $view = $this->definition->getPrefixView();
//        //        echo $view . '<br />';
//    }

//    private function setColumn(Column $column, $index)
//    {
//
//        if (is_int($index)) {
//            $this->columns[$index] = $column;
//        } else if (is_string($index) && $index == 'first') {
//            array_unshift($this->columns, $column);
//        } else {
//            $this->columns[] = $column;
//        }
//
//        return $column;
//    }
//
//    /**
//     * @param string     $title
//     * @param int|string $index
//     *
//     * @return Column
//     */
//    protected function addColumn($title, $index = null)
//    {
//
//        $column = new Column($title);
//
//        return $this->setColumn($column, $index);
//    }

    /**
     * @param int|string $index
     *
     * @return Column
     */
    protected function addEmptyColumn($index = null)
    {

        return $this->addColumn('', $index);
    }

    /**
     * @return Column
     */
    protected function addIdColumn()
    {

        $column = new Id();

        return $this->setColumn($column, null);
        //        return $this->addColumn('Id')->setSortable()->setWidth("60px");
    }

    protected function addAuditColumn()
    {

        $column = new Audit();

        return $this->setColumn($column, null);
    }

    protected function addNoteColumn()
    {

        $column = new Note();

        return $this->setColumn($column, null);
    }

    protected function addActionColumn()
    {

        $column = new Action();

        return $this->setColumn($column, null);
    }

//    /**
//     * @return array
//     */
//    public function getColumns()
//    {
//
//        return $this->columns;
//    }
//
//    /**
//     * @param int $index
//     *
//     * @return Column
//     * @throws \OutOfBoundsException
//     */
//    public function getColumn($index)
//    {
//
//        if (array_key_exists($index, $this->columns)) {
//            return $this->columns[$index];
//        }
//
//        throw new \OutOfBoundsException('Column index ' . $index . ' does not exist');
//    }

//    /**
//     * @param string $action
//     *
//     * @throws \InvalidArgumentException
//     */
//    private function actionExists($action)
//    {
//
//        if (!array_key_exists($action, $this->actions)) {
//            throw new \InvalidArgumentException('Unknown action: "' . $action . '"');
//        }
//    }
//
//    /**
//     * @param string $action
//     */
//    public function allowAction($action)
//    {
//
//        $this->actionExists($action);
//
//        $this->actions[$action] = true;
//    }
//
//    /**
//     * @param string $action
//     */
//    public function disallowAction($action)
//    {
//
//        $this->actionExists($action);
//
//        $this->actions[$action] = false;
//    }
//
//    /**
//     * @param string $action
//     *
//     * @return bool
//     */
//    public function isAllowed($action)
//    {
//
//        $this->actionExists($action);
//
//        return $this->actions[$action];
//    }

    /**
     * @param array $entries
     */
    public function setEntries($entries)
    {

        $this->entries = $entries;
    }

    /**
     * @return array
     */
    public function getEntries()
    {

        return $this->entries;
        //        $entries = array();
        //
        //        $model = new Model();
        //        $model->setId(1);
        //        $model->setName('test');
        //        $model->setDescription('asdf');
        //        $entries[] = $model;
        //
        //        $model = new Model();
        //        $model->setId(2);
        //        $model->setName('123test');
        //        //        $model->setDescription('asdf');
        //        $entries[] = $model;

        return $entries;
    }

    public function hasFilters()
    {

        return count($this->filters) != 0;
        //        return true;
    }

    public function hasPagination()
    {

        // URGENT implement

        return true;
    }

    public function hasSearch()
    {

        foreach ($this->columns as $column) {
            if ($column->isSearchable()) {
                return true;
            }
        }
    }

    protected function addFilter(Definition $definition)
    {

        //        echo '<pre>';
        //        var_dump($definition);
        //        echo '</pre>';
        $this->filters[] = $definition;
    }

    private function addCustomFilter()
    {
    }

    /**
     * @return array
     */
    public function getFilters()
    {

        $builder = $this->container->get('form.factory')->createBuilder();
        $filters = array();
        foreach ($this->filters as $singleFilter) {

            $field     = $builder->create(
                lcfirst($singleFilter->getName()),
                'entity',
                array(
                    'label'       => false,
                    'required'    => false,
                    'empty_value' => '- Please Select ?? - ', // TRANSLATE select option for filters
                    'class'       => $singleFilter->getClassEntity(),
                    'property'    => 'name', // URGENT make property field for filters generic, maybe add to definition
                    'attr'        => array(
                        'onchange' => 'jQuery(this).closest(\'form\').trigger(\'submit\');',
                    ),
                )
            )->getForm()->createView();
            $filters[] = $field;
        }

        return $filters;
    }
}