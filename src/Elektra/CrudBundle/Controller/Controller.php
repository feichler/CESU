<?php

namespace Elektra\CrudBundle\Controller;

use Elektra\CrudBundle\Crud\Crud;
use Elektra\CrudBundle\Crud\Definition;
use Elektra\CrudBundle\Form\Form;
use Elektra\CrudBundle\Table\Table;
use Elektra\SeedBundle\Entity\EntityInterface;
use Elektra\SiteBundle\Site\Helper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;

abstract class Controller extends BaseController
{

    /**
     * @var Crud
     */
    protected $crud;

    protected $filterSubmitted = false;

    /**
     * @param string     $action
     * @param Definition $definition
     */
    protected final function initialise($action, Definition $definition = null)
    {

        if ($definition === null) {
            $this->crud = new Crud($this, $this->getDefinition());
        } else {
            $this->crud = new Crud($this, $definition);
        }
        $siteBase = $this->get('siteBase');
        $siteBase->initialisePageFromDefinition($this->getCrud()->getDefinition(), $action);

        $filtersSubmit = $this->get('request')->get('filter-submit');
        if ($filtersSubmit !== null) {
            $this->filterSubmitted = true;
        }

        //        exit();
        //        $test = $this->getRequest()->get('filter-submit');
        //        if($test !== null) {
        //            var_dump($this->getRequest()->get('filter'));
        //                exit();
        //        }
        //                var_dump($test);
        //                exit();
    }

    /**
     * @return Crud
     */
    public function getCrud()
    {

        return $this->crud;
    }

    /*************************************************************************
     * Controller Actions
     *************************************************************************/

    /**
     * @param int $page
     *
     * @return Response
     */
    public function browseAction($page)
    {

        $this->initialise('browse');

        // store the actual browsing page for return
        $this->getCrud()->setData('page', $page, 'browse');

        // load the table with the entries for browsing
        $table = $this->getTable($page);

        // get the view name (specific or common)
        $viewName = $this->getCrud()->getView('browse');

        // render the browse-view with the table
        return $this->render($viewName, array('table' => $table));
    }

    /**
     * @param int|null $id
     *
     * @return Response
     */
    public function addAction($id = null)
    {

        $this->initialise('add');

        // get a fresh entity
        $entity = $this->getEntityForAdd($id);

        // get the associated form
        $form = $this->getForm($entity, 'add');

        // check the form
        $form->handleRequest($this->getCrud()->getRequest());

        if ($form->isValid() && !$this->filterSubmitted) {
            return $this->processAction('add', $entity, $form);
        }

        // get the view name (specific or common) and prepare the form view
        $viewName = $this->getCrud()->getView('form');
        $formView = $form->createView();

        // render the add-view with the form
        return $this->render($viewName, array('form' => $formView));
    }

    /**
     * @param int $id
     *
     * @return Response
     */
    public function viewAction($id)
    {

        $this->initialise('view');

        // get the existing entity
        $entity = $this->getEntity($id);

        // get the associated form
        $form = $this->getForm($entity, 'view');

        // get the view name (specific or common) and prepare the form view
        $viewName = $this->getCrud()->getView('view');
        $formView = $form->createView();

        // render the add-view with the form
        return $this->render($viewName, array('form' => $formView));
    }

    /**
     * @param int $id
     *
     * @return Response
     */
    public function editAction($id)
    {

        $this->initialise('edit');

        // get the existing entity
        $entity = $this->getEntity($id);

        // get the associated form
        $form = $this->getForm($entity, 'edit');

        // check the form
        $form->handleRequest($this->getCrud()->getRequest());
        if ($form->isValid() && !$this->filterSubmitted) {
            return $this->processAction('edit', $entity, $form);
        }
        //var_dump($this->getCrud()->getLinker()->getRedirectAfterProcess($entity));
        // get the view name (specific or common) and prepare the form view
        $viewName = $this->getCrud()->getView('form');
        $formView = $form->createView();

        // render the add-view with the form
        return $this->render($viewName, array('form' => $formView));
    }

    /**
     * @param int $id
     *
     * @return Response
     */
    public function deleteAction($id)
    {

        $this->initialise('delete');

        // get the existing entity
        $entity = $this->getEntity($id);

        return $this->processAction('delete', $entity);
    }

    /**
     * @param EntityInterface $parentEntity
     * @param string          $parentRoute
     * @param string|null     $relationName
     *
     * @return Response
     */
    public function relatedListAction(EntityInterface $parentEntity, $parentRoute, $relationName = null, $options = null)
    {

        // URGENT define this action
        $this->initialise('relatedList');
        $this->getCrud()->setParent($parentEntity, $parentRoute, $relationName);

        $table = $this->getTable(1, false);
        $table->setInView(true);
        $table->setOptions($options);
        $table->load(1);

        $viewName = $this->getCrud()->getView('relatedList');

        return $this->render($viewName, array('table' => $table));
    }

    /**
     * @param EntityInterface $parentEntity
     * @param Definition      $childType
     * @param string          $parentRoute
     * @param string|null     $relationName
     *
     * @return Response
     */
    public function childListAction(EntityInterface $parentEntity, Definition $childType, $parentRoute, $relationName = null)
    {

        $this->initialise('childList', $childType);
        $this->getCrud()->setParent($parentEntity, $parentRoute, $relationName);

        $table = $this->getTable(1, false);
        $table->setInView(true);
//        $table->setOptions($options);
        $method = 'get' . ucfirst($relationName);

        if (method_exists($parentEntity, $method)) {
            $table->setEntries($parentEntity->$method());
        }

        $viewName = $this->getCrud()->getView('childList');

        return $this->render($viewName, array('table' => $table));
    }

    /*************************************************************************
     * Controller Action Helpers
     *************************************************************************/

    /**
     * @param string        $action
     * @param mixed         $entity
     * @param FormInterface $form
     *
     * @return Response
     */
    protected function processAction($action, $entity, FormInterface $form = null)
    {

        $manager = $this->getDoctrine()->getManager();

        // call the before hook
        $beforeHook = 'before' . ucfirst($action) . 'Entity';
        $result     = $this->$beforeHook($entity, $form);

        if ($result) {
            switch ($action) {
                case 'add':
                    // add the entity to the manager
                    $manager->persist($entity);
                    break;
                case 'edit':
                    // nothing to do, flush updates the entity anyway
                    break;
                case 'delete':
                    // get a reference of the entity
                    if (is_int($entity)) {
                        $ref = $manager->getReference($this->getCrud()->getDefinition()->getClassEntity(), $entity);
                        $manager->remove($ref);
                    } else {
                        $manager->remove($entity);
                    }
                    break;
                default:
                    // URGENT display error for unknown action
                    break;
            }

            // action has been performed -> flush the manager
            $manager->flush();

            // call the after hook
            $afterHook = 'after' . ucfirst($action) . 'Entity';
            $this->$afterHook($entity, $form);

            // flush the manager again, in case the after action has modified something
            $manager->flush();
        }

        $returnUrl = $this->getCrud()->getLinker()->getRedirectAfterProcess($entity);

        //        $returnUrl = $this->getCrud()->getAfterProcessReturnUrl();

        return $this->redirect($returnUrl);
    }

    /**
     * @param int  $page
     * @param bool $autoload
     *
     * @return Table
     */
    protected function getTable($page, $autoload = true)
    {

        $tableClass = $this->getCrud()->getDefinition()->getClassTable();
        $table      = new $tableClass($this->getCrud());

        if ($autoload) {
            $table->load($page);
        }

        return $table;
    }

    /**
     * @param int|null $id
     *
     * @return EntityInterface
     */
    protected function getEntityForAdd($id = null)
    {

        $entityClass = $this->getCrud()->getDefinition()->getClassEntity();
        $entity      = new $entityClass();

        if ($id !== null) {
            // URGENT implement
        }

        return $entity;
    }

    /**
     * @param int $id
     *
     * @return EntityInterface
     */
    protected function getEntity($id)
    {

        $repositoryClass = $this->getCrud()->getDefinition()->getClassRepository();
        $repository      = $this->getDoctrine()->getRepository($repositoryClass);
        $entity          = $repository->find($id);

        return $entity;
    }

    /**
     * @param EntityInterface $entity
     * @param string          $crudAction
     *
     * @return Form
     */
    protected function getForm(EntityInterface $entity, $crudAction)
    {

        $formClass = $this->getCrud()->getDefinition()->getClassForm();
        $options   = Helper::mergeOptions(
            array('crud_action' => $crudAction),
            $this->getFormOptions($entity, $crudAction)
        );

        $form = $this->createForm(new $formClass($this->getCrud()), $entity, $options);

        return $form;
    }

    /**
     * @param EntityInterface $entity
     * @param string          $crudAction
     *
     * @return array
     */
    protected function getFormOptions($entity, $crudAction)
    {

        // NOTE override if necessary
        return array();
    }

    /*************************************************************************
     * CRUD Hooks
     *************************************************************************/

    /**
     * @param EntityInterface $entity
     * @param FormInterface   $form
     *
     * @return bool
     */
    public function beforeAddEntity(EntityInterface $entity, FormInterface $form = null)
    {

        // NOTE override if necessary
        // DEFINE: if false is returned, the action won't be processed
        return true;
    }

    /**
     * @param EntityInterface $entity
     * @param FormInterface   $form
     */
    public function afterAddEntity(EntityInterface $entity, FormInterface $form = null)
    {
        // NOTE override if necessary
    }

    /**
     * @param EntityInterface $entity
     * @param FormInterface   $form
     *
     * @return bool
     */
    public function beforeEditEntity(EntityInterface $entity, FormInterface $form = null)
    {

        // NOTE override if necessary
        // DEFINE: if false is returned, the action won't be processed

        return true;
    }

    /**
     * @param EntityInterface $entity
     * @param FormInterface   $form
     */
    public function afterEditEntity(EntityInterface $entity, FormInterface $form = null)
    {
        // NOTE override if necessary
    }

    /**
     * @param EntityInterface $entity
     * @param FormInterface   $form
     *
     * @return bool
     */
    public function beforeDeleteEntity(EntityInterface $entity, FormInterface $form = null)
    {

        // NOTE override if necessary
        // DEFINE: if false is returned, the action won't be processed

        return true;
    }

    /**
     * @param EntityInterface $entity
     * @param FormInterface   $form
     */
    public function afterDeleteEntity(EntityInterface $entity, FormInterface $form = null)
    {
        // NOTE override if necessary
    }


    /*************************************************************************
     * Abstract and overridable methods for inheriting controllers
     *************************************************************************/

    /**
     * @return Definition
     */
    protected abstract function getDefinition();
}