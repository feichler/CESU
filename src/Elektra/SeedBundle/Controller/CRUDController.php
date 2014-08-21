<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Controller;

    //use Elektra\SeedBundle\Form\CRUDFilters;
//use Elektra\SeedBundle\Table\CRUDTable;
use Elektra\SeedBundle\Form\CRUDFilters;
use Elektra\SeedBundle\Table\CRUDTable;
use Elektra\SiteBundle\Navigator\Definition;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CRUDController
 *
 * @package Elektra\SeedBundle\Controller
 *
 * @version 0.1-dev
 */
abstract class CRUDController extends Controller
{

    /*************************************************************************
     * Process of the CRUDController:
     *      - starting point: <type>Action() -> calls initialise(<type>)
     *          - calls the abstract setup(<type>) method
     *              the setup method is required to set all:
     *                  - prefixes
     *                  - classes
     *                  - language keys
     *          - checks if all variables are set up
     *          - gets the default options for the page initialisation
     *          - defines required overrides based on <type>
     *          - calls the initialiseAdminPage method on the global page obj.
     *      - continue to standard processing
     *************************************************************************/

    /**
     * @var Definition
     */
    protected $definition;

    /*************************************************************************
     * Controller Functions
     *************************************************************************/

    /**
     * @param Request $request
     * @param int     $page
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function browseAction(Request $request, $page)
    {

        // Initialise the controller (does initialise the page as well)
        $this->initialise('browse');

        $browseFilters = $this->getBrowseFilters();
       $selectFilters =  $this->loadBrowseFilterData($request,$browseFilters);
//        var_dump($selectFilters);
        //$filters = $this->loadFilterData($request);
        // save the page number
        $this->setPage($page);

        // get the required classes for this action
        $repositoryClass = $this->definition->getClassRepository();
        $tableClass      = $this->definition->getClassTable();

        // execute the required actions for this controller
        $repository = $this->getDoctrine()->getRepository($repositoryClass);
        $table      = new $tableClass();
        $table->setNavigator($this->get('navigator'), $this->definition->getKey());

        //        $entries = $repository->getEntries($page, $table->getPagination()->getLimit());
        $entries = $repository->getEntries($page, $table->getPagination()->getLimit(), $selectFilters);

        $table->getPagination()->setPage($page);
        $table->getPagination()->setCount($repository->getCount());
        $table->prepare($entries);

        $filters = new CRUDFilters();
        foreach($browseFilters as $key => $filter) {
            $filters->addFilter($key, $filter);
        }
//        echo 'SELECTFILTERS: <br />';
//        var_dump($selectFilters);
//        echo '<br />';
        $filterForm = $this->createForm($filters, $selectFilters, array());
        //        $this->addBrowseFilters($table, $filters);
        // generate the view name
        $viewName   = $this->getView('browse');
        $filterView = $filterForm->createView();

        // return the response
        return $this->render($viewName, array('table' => $table, 'filters' => $filterView));
//        return $this->render($viewName, array('table' => $table));
    }

    protected function getBrowseFilters()
    {

        // NOTE: inherting class needs to override this method to add filters to the browse view
        // NOTE array must be structured like key = field name in entity, value = definition for the crud entity

        return array();
    }

    protected function loadBrowseFilterData(Request $request, $browseFilters)
    {

        $crudFilters = $request->get('crudfilters', array());
//var_dump($crudFilters);
        $return = array();

        foreach($browseFilters as $key => $definition) {
            if(array_key_exists($key, $crudFilters)) {
                $filterValue = $crudFilters[$key];
                if(!empty($filterValue)) {
                    $return[$key] = $this->getDoctrine()->getRepository($definition->getClassRepository())->find($filterValue);
$this->setVar($key, $filterValue);
                } else {
                    $this->setVar($key, null);
                }
//                 echo 'KEY '.$key. ' => '.$filterValue.'<br />';
            }

            if(!array_key_exists($key, $return)) {
                // check if key is stored in session
                $value = $this->getVar($key);
                if($value !== null) {
                    $return[$key] = $value;
                }
            }
        }
//        var_dump($return);
return $return;
//        var_dump($browseFilters);
//        echo '<br />';
//         TODO
//        var_dump($crudFilters);
    }

    //    protected function loadFilterData(Request $request)
    //    {
    //
    //        echo 'Loading filter data<br />';
    //
    //        $crudFilters = $request->get('crudfilters', array());
    //
    //        $filters = array();
    //        if (isset($crudFilters['seedunitmodel'])) {
    //            $filters['model'] = $crudFilters['seedunitmodel'];
    //        }
    //
    //        return $filters;
    //        var_dump($filters);
    //        //        $test = $request->get('crudfilters');
    //        //        var_dump($test);
    //
    //        //        $test = $request->get('crudfilters_seedunitmodel');
    //        //        echo $test;
    //    }

    //    protected function addBrowseFilters(CRUDTable $table, $filters)
    //    {
    //
    //        $crudFilters = new CRUDFilters();
    //        $crudFilters->addFilter();
    //        $form       = $this->createForm($crudFilters);
    //        $filterView = $form->createView();
    //        $table->addFilters($filterView);
    //    }

    /**
     * @param Request $request
     * @param int     $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction(Request $request, $id)
    {

        // Initialise the controller (does initialise the page as well)
        $this->initialise('view');

        // get the required classes for this action
        $repositoryClass = $this->definition->getClassRepository();
        $formClass       = $this->definition->getClassForm();

        // execute the required actions for this controller
        $repository = $this->getDoctrine()->getRepository($repositoryClass);
        $entity     = $repository->find($id);
        $returnLink = $this->get('navigator')->getLink($this->definition, 'browse', array('page' => $this->getPage()));

        $form = $this->createForm(
            new $formClass(),
            $entity,
            array(
                'returnLink' => $returnLink,
                'crudAction' => 'view',
            )
        );

        // generate the view & view name
        $view     = $form->createView();
        $viewName = $this->getView('view');

        // return the response
        return $this->render($viewName, array('form' => $view));
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request)
    {

        // Initialise the controller (does initialise the page as well)
        $this->initialise('add');

        // get the required classes for this action
        $entityClass = $this->definition->getClassEntity();
        $formClass   = $this->definition->getClassForm();

        // execute the required actions for this controller
        $entity = new $entityClass();
        $form   = $this->createForm(
            new $formClass(),
            $entity,
            array(
                'crudAction' => 'add',
            )
        );
        $form->handleRequest($request);

        if ($form->isValid() && $form->get('actions')->get('save')->isClicked()) {
            // save the entity and redirect to browsing
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($entity);
            $manager->flush();

            $this->addSuccessMessage('add', $entity->getId());

            return $this->redirectToBrowse();
        } else if ($form->get('actions')->get('cancel')->isClicked()) {
            // do nothing (discard the form) and redirect to browsing
            return $this->redirectToBrowse();
        }

        // generate the view & view name
        $view     = $form->createView();
        $viewName = $this->getView('form');

        // return the response
        return $this->render($viewName, array('form' => $view));
    }

    /**
     * @param Request $request
     * @param int     $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, $id)
    {

        // Initialise the controller (does initialise the page as well)
        $this->initialise('edit');

        // get the required classes for this action
        $repositoryClass = $this->definition->getClassRepository();
        $formClass       = $this->definition->getClassForm();

        // execute the required actions for this controller
        $repository = $this->getDoctrine()->getRepository($repositoryClass);
        $entity     = $repository->find($id);
        $form       = $this->createForm(
            new $formClass(),
            $entity,
            array(
                'crudAction' => 'edit',
            )
        );
        $form->handleRequest($request);

        if ($form->isValid() && $form->get('actions')->get('save')->isClicked()) {
            // save the entity and redirect to browsing
            $manager = $this->getDoctrine()->getManager();
            $manager->flush();

            $this->addSuccessMessage('edit', $entity->getId());

            return $this->redirectToBrowse();
        } else if ($form->get('actions')->get('cancel')->isClicked()) {
            // do nothing (discard the form) and redirect to browsing
            return $this->redirectToBrowse();
        }

        // generate the view & view name
        $view     = $form->createView();
        $viewName = $this->getView('form');

        // return the response
        return $this->render($viewName, array('form' => $view));
    }

    /**
     * @param Request $request
     * @param int     $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction(Request $request, $id)
    {

        // Initialise the controller (does initialise the page as well)
        $this->initialise('delete');

        // get the required classes for this action
        $repositoryClass = $this->definition->getClassRepository();

        // execute the required actions for this controller
        $repository = $this->getDoctrine()->getRepository($repositoryClass);
        $entity     = $repository->find($id);

        // remove the entity
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($entity);
        $manager->flush();

        $this->addSuccessMessage('delete', $id);

        // redirect to browsing
        return $this->redirectToBrowse();
    }
    /*************************************************************************
     * Controller Initialisation
     *************************************************************************/

    /**
     * @param $action
     */
    protected final function initialise($action)
    {

        $this->loadDefinition();

        $options = $this->getInitialiseOptions();

        $page = $this->container->get('page');
        $page->initialiseAdminPage('admin', $action, $options);
    }

    /**
     * @return array
     */
    protected final function getInitialiseOptions()
    {

        $options = parent::getInitialiseOptions();

        // TODO add overrides?

        return $options;
    }

    /**
     *
     */
    protected abstract function loadDefinition();

    /*************************************************************************
     * Generic Helper functions
     *************************************************************************/

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    private function redirectToBrowse()
    {

        $page = $this->getPage();
        $link = $this->get('navigator')->getLink($this->definition->getKey(), 'browse', array('page' => $page));

        return $this->redirect($link);
    }

    /**
     * @param string $type
     *
     * @return string
     * @throws \RuntimeException
     */
    private function getView($type)
    {

        $templateService = $this->get('templating');
        $prefix          = $this->definition->getViewPrefix();
        $prefixCommon    = 'ElektraSeedBundle::';

        // First check if the specific view exists
        $specific = $prefix . ':' . $type . '.html.twig';
        if ($templateService->exists($specific)) {
            return $specific;
        } else {
            // if the specific view does not exist, try to get a common view
            $common = $prefixCommon . 'base-' . $type . '.html.twig';
            if ($templateService->exists($common)) {
                return $common;
            } else {
                // if neither specific nor common view are found, we have a RuntimeException
                throw new \RuntimeException('Fatal Error: View of type "' . $type . '" could not be located');
            }
        }
    }

    /*************************************************************************
     * Message Helper functions
     *************************************************************************/

    /**
     * @param string      $action
     * @param int|null    $id
     * @param string|null $name
     */
    protected function addSuccessMessage($action, $id = null, $name = null)
    {

        $this->addActionMessage('success', $action, $id, $name);
    }

    /**
     * @param string      $action
     * @param int|null    $id
     * @param string|null $name
     */
    protected function addErrorMessage($action, $id = null, $name = null)
    {

        $this->addActionMessage('error', $action, $id, $name);
    }

    /**
     * @param string      $type
     * @param string      $action
     * @param int|null    $id
     * @param string|null $name
     */
    protected function addActionMessage($type, $action, $id = null, $name = null)
    {

        $message = '';

        // TRANSLATE add default message translations
        $message = 'MSG action: ' . $action;
        if ($id != null) {
            $message .= ' - ID: ' . $id;
        }
        if ($name != null) {
            $message .= ' - Name: ' . $name;
        }

        $this->addMessage($type, $message);
    }

    /*************************************************************************
     * Standard Getters / Setters
     *************************************************************************/

    private function setVar($name,$value) {

        $this->get('session')->set($this->getVarName($name),$value);
    }
    private function getVar($name) {
        return $this->get('session')->get($this->getVarName($name), null);
    }
    private function getVarName($name) {
        $name = $this->definition->getClassEntity().'-'.$name;
        return $name;
    }

    /**
     * @return int
     */
    private function getPage()
    {
return $this->getVar('browse.page');
//        return $this->get('session')->get($this->definition->getRouteNamePrefix() . '.page');
    }

    /**
     * @param int $page
     */
    private function setPage($page)
    {
$this->setVar('browse.page',$page);
//        $this->get('session')->set($this->definition->getRouteNamePrefix() . '.page', $page);
    }
}