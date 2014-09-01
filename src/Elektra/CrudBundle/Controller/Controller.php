<?php

namespace Elektra\CrudBundle\Controller;

use Elektra\CrudBundle\Crud\Crud;
use Elektra\SeedBundle\Entity\EntityInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;

abstract class Controller extends BaseController
{

    /**
     * @var Crud
     */
    protected $crud;

    /**
     * @param string $action
     */
    protected final function initialise($action)
    {

        $this->crud = new Crud($this, $this->getDefinition());

        $siteBase = $this->get('siteBase');
        $siteBase->initialisePageFromDefinition($this->getCrud()->getDefinition(), $action);
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

    public function addAction($id = null)
    {

        $this->initialise('add');

        // get a fresh entity
        $entity = $this->getEntityForAdd($id);

        // get the associated form
        $form = $this->getForm($entity, 'add');

        // check the form
        $form->handleRequest($this->getCrud()->getRequest());
        if ($form->isValid()) {
            return $this->processAction('add', $entity);
        }

        // get the view name (specific or common) and prepare the form view
        $viewName = $this->getCrud()->getView('form');
        $formView = $form->createView();

        // render the add-view with the form
        return $this->render($viewName, array('form' => $formView));
    }

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

    public function editAction($id)
    {

        $this->initialise('edit');

        // get the existing entity
        $entity = $this->getEntity($id);

        // get the associated form
        $form = $this->getForm($entity, 'edit');

        // check the form
        $form->handleRequest($this->getCrud()->getRequest());
        if ($form->isValid()) {
            return $this->processAction('edit', $entity);
        }
//var_dump($this->getCrud()->getLinker()->getRedirectAfterProcess($entity));
        // get the view name (specific or common) and prepare the form view
        $viewName = $this->getCrud()->getView('form');
        $formView = $form->createView();

        // render the add-view with the form
        return $this->render($viewName, array('form' => $formView));
    }

    public function deleteAction($id)
    {

        $this->initialise('delete');

        // get the existing entity
        $entity = $this->getEntity($id);

        return $this->processAction('delete', $entity);
    }

    public function relatedListAction(EntityInterface $parentEntity, $parentRoute,$relationName = null)
    {

        // URGENT define this action
        $this->initialise('relatedList');
        $this->getCrud()->setParent($parentEntity,$parentRoute,$relationName);

        $table = $this->getTable(1);

        $viewName = $this->getCrud()->getView('relatedList');

        return $this->render($viewName, array('table' => $table));
    }

    /*************************************************************************
     * Controller Action Helpers
     *************************************************************************/

    protected function processAction($action, $entity)
    {

        $manager = $this->getDoctrine()->getManager();

        // call the before hook
        $beforeHook = 'before' . ucfirst($action) . 'Entity';
        $result     = $this->$beforeHook($entity);

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
            $this->$afterHook($entity);
        }

        $returnUrl = $this->getCrud()->getLinker()->getRedirectAfterProcess($entity);

        //        $returnUrl = $this->getCrud()->getAfterProcessReturnUrl();

        return $this->redirect($returnUrl);
    }

    protected function getTable($page)
    {

        $tableClass = $this->getCrud()->getDefinition()->getClassTable();
        $table      = new $tableClass($this->getCrud());

        $table->load($page);

        return $table;
    }

    protected function getEntityForAdd($id = null)
    {

        $entityClass = $this->getCrud()->getDefinition()->getClassEntity();
        $entity      = new $entityClass();

        if ($id !== null) {
            // URGENT implement
        }

        return $entity;
    }

    protected function getEntity($id)
    {

        $repositoryClass = $this->getCrud()->getDefinition()->getClassRepository();
        $repository      = $this->getDoctrine()->getRepository($repositoryClass);
        $entity          = $repository->find($id);

        return $entity;
    }

    protected function getForm(EntityInterface $entity, $crudAction)
    {

        $formClass = $this->getCrud()->getDefinition()->getClassForm();
        $options   = $this->getCrud()->mergeOptions(
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
     *
     * @return bool
     */
    public function beforeAddEntity(EntityInterface $entity)
    {

        // NOTE override if necessary
        // DEFINE: if false is returned, the action won't be processed
        return true;
    }

    /**
     * @param EntityInterface $entity
     */
    public function afterAddEntity(EntityInterface $entity)
    {
        // NOTE override if necessary
    }

    /**
     * @param EntityInterface $entity
     *
     * @return bool
     */
    public function beforeEditEntity(EntityInterface $entity)
    {

        // NOTE override if necessary
        // DEFINE: if false is returned, the action won't be processed

        return true;
    }

    /**
     * @param EntityInterface $entity
     */
    public function afterEditEntity(EntityInterface $entity)
    {
        // NOTE override if necessary
    }

    /**
     * @param EntityInterface $entity
     *
     * @return bool
     */
    public function beforeDeleteEntity(EntityInterface $entity)
    {

        // NOTE override if necessary
        // DEFINE: if false is returned, the action won't be processed

        return true;
    }

    /**
     * @param EntityInterface $entity
     */
    public function afterDeleteEntity(EntityInterface $entity)
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