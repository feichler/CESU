<?php

namespace Elektra\CrudBundle\Controller;

use Elektra\CrudBundle\Crud\Crud;
use Elektra\CrudBundle\Definition\Definition;
//use Elektra\CrudBundle\Form\Form;
use Elektra\SeedBundle\Entity\EntityInterface;
use Elektra\SeedBundle\Table\SeedUnits\ModelTable;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request as HTTPRequest;
use Symfony\Component\HttpFoundation\Response as HTTPResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;

abstract class Controller extends BaseController
{

    /**
     * @var Crud
     */
    protected $crud;

    protected function initialise($action)
    {

        $siteBase   = $this->get('siteBase');
        $this->crud = new Crud($this, $this->getDefinition());

        $siteBase->initialisePageFromDefinition($this->crud->getDefinition(), $action);
    }

    /**
     * @return Crud
     */
    public function getCrud()
    {

        return $this->crud;
    }

    /**
     * @return Definition
     */
    protected abstract function getDefinition();

    /*************************************************************************
     * Controller methods
     *************************************************************************/

    /**
     * @param HTTPRequest $request
     * @param int         $page
     *
     * @return HTTPResponse
     */
    public function browseAction(HTTPRequest $request, $page)
    {

        $this->initialise('browse');
        $this->crud->save('page', $page, 'browse');

        $tableClass = $this->getDefinition()->getClassTable();
        $table      = new $tableClass($this->getCrud());
        // the load method on the table does all required tasks for the execution
        $table->load($page);

        // return the response -> viewname with parameters "table" & "filters"
        $view = $this->crud->getView('browse');

        return $this->render($view, array('table' => $table));
    }

    /**
     * @param HTTPRequest $request
     *
     * @return HTTPResponse
     */
    public function addAction(HTTPRequest $request)
    {

        $this->initialise('add');

        $entityClass = $this->getDefinition()->getClassEntity();

        // execute the required actions for this controller
        $entity = new $entityClass();
        $form   = $this->getForm($entity, 'add');
        $form->handleRequest($request);

        if ($form->isValid()) { // process the form
            return $this->process($entity, 'add');
        }

        $view     = $this->getCrud()->getView('form');
        $formView = $form->createView();

        return $this->render($view, array('form' => $formView));
    }


    public function viewAction(HTTPRequest $request, $id)
    {

        $this->initialise('view');
        $this->crud->save('viewUrl',$request->getUri());

        $repositoryClass = $this->getCrud()->getDefinition()->getClassRepository();

        // execute the required actions for this controller
        $repository = $this->getDoctrine()->getRepository($repositoryClass);
        $entity     = $repository->find($id);
        $form       = $this->getForm($entity, 'view');

        $view     = $this->getCrud()->getView('view');
        $formView = $form->createView();

        return $this->render($view, array('form' => $formView));
    }

    public function editAction(HTTPRequest $request, $id)
    {

        $this->initialise('add');

        $repositoryClass = $this->getCrud()->getDefinition()->getClassRepository();

        // execute the required actions for this controller
        $repository = $this->getDoctrine()->getRepository($repositoryClass);
        $entity     = $repository->find($id);
        $form       = $this->getForm($entity, 'edit');
        $form->handleRequest($request);

        if ($form->isValid()) { // process the form
            return $this->process($entity, 'edit');
        }

        $view     = $this->getCrud()->getView('form');
        $formView = $form->createView();

        return $this->render($view, array('form' => $formView));
    }

    public function deleteAction(HTTPRequest $request, $id)
    {

        $this->initialise('delete');

        return $this->process($id, 'delete');
    }

    public function relatedListAction(Definition $definition, EntityInterface $parent, $relationName)
    {

        $this->initialise('relatedList');
//$url = $this->getCrud()->get('viewUrl');
        $this->getCrud()->setEmbedded($definition, $parent, $relationName);

//        echo 'viewUrl: '.$url.'<br />';
//        $request = $this->get('request');
//
//        echo $definition->getKey().'<br />';
//        $this->crud->save('return', 'asdf');

//        if($request instanceof HTTPRequest){
////            $request->
//            echo '<br /><br />';
//            echo 'URL: <br />';
//            $url = $request->getUri();
//            echo '1: '. $url;
//            echo '<br />';
//            $test = $request->getRequestUri();
//            echo '1: '. $test;
//            echo '<br />';
//            $test2 = $request->get('_route');
//            echo '1: '. $test2;
//            echo '<br />';
//            echo '<br />';
//        }
//        var_dump($this->get('session')->all());



        $tableClass = $definition->getClassTable();
        $table      = new $tableClass($this->getCrud());
//        $table      = new $tableClass($this->getCrud(), $definition);
//        $table->setRelation($relation, $entity);
        // the load method on the table does all required tasks for the execution
        $table->load(1);

        $view = $this->getCrud()->getView('relatedList');

        return $this->render($view, array('table' => $table));
    }

    /*************************************************************************
     * execution methods
     *************************************************************************/

    public function process($entity, $crudAction)
    {

        $manager = $this->getDoctrine()->getManager();

        // call the beforeHook
        $beforeHook = 'before' . ucfirst($crudAction) . 'Entity';
        $result     = $this->$beforeHook($entity);

        if ($result) {
            if ($crudAction == 'add') {
                $manager->persist($entity);
            } else if ($crudAction == 'edit') {
                // nothing more to do - only flush
            } else if ($crudAction == 'delete') {
                // $entity is int for delete -> need to load the reference entity
                $ref = $manager->getReference($this->crud->getDefinition()->getClassEntity(), $entity);
                $manager->remove($ref);
            }

            $manager->flush();

            // call the afterHook
            $afterHook = 'after' . ucfirst($crudAction) . 'Entity';
            $this->$afterHook($entity);
            // TODO add a status message!
        } else {
            // TODO add message -> before hook rejected action
        }

        return $this->redirect($this->crud->getActiveBrowseLink());
    }

    /*************************************************************************
     * preparation methods
     *************************************************************************/

    public function getForm(EntityInterface $entity, $crudAction)
    {

        $formClass = $this->crud->getDefinition()->getClassForm();
        $options   = array_merge_recursive(
            array(
                'crud_action' => $crudAction,
            ),
            $this->getFormOptions($entity, $crudAction)
        );

        $form = $this->createForm(new $formClass($this->getCrud()), $entity, $options);

        return $form;
    }

    /*************************************************************************
     * Hooks for specific controllers
     *************************************************************************/

    /**
     * @param EntityInterface $entity
     * @param string          $crudAction
     *
     * @return array
     */
    public function getFormOptions($entity, $crudAction)
    {

        // NOTE override if necessary
        return array();
    }

    /**
     * @param EntityInterface $entity
     *
     * @return bool
     */
    protected function beforeViewEntity($entity)
    {

        // NOTE override if necessary
        // DEFINE: if false is returned, the entity won't be displayed
        return true;
    }

    /**
     * @param EntityInterface $entity
     */
    protected function afterViewEntity($entity)
    {
        // NOTE override if necessary
    }

    /**
     * @param EntityInterface $entity
     *
     * @return bool
     */
    protected function beforeAddEntity($entity)
    {

        // NOTE override if necessary
        // DEFINE: if false is returned, the entity won't be persisted
        return true;
    }

    /**
     * @param EntityInterface $entity
     */
    protected function afterAddEntity($entity)
    {
        // NOTE override if necessary
    }

    /**
     * @param EntityInterface $entity
     *
     * @return bool
     */
    protected function beforeEditEntity($entity)
    {

        // NOTE override if necessary
        // DEFINE: if false is returned, the entity won't be updated
        return true;
    }

    /**
     * @param EntityInterface $entity
     */
    protected function afterEditEntity($entity)
    {
        // NOTE override if necessary
    }

    /**
     * @param EntityInterface $entity
     *
     * @return bool
     */
    protected function beforeDeleteEntity($entity)
    {

        // NOTE override if necessary
        // DEFINE: if false is returned, the entity won't be deleted
        return true;
    }

    /**
     * @param EntityInterface $entity
     */
    protected function afterDeleteEntity($entity)
    {
        // NOTE override if necessary
    }
}