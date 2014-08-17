<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Controller;

use Elektra\SiteBundle\Navigator\Definition;
use Elektra\ThemeBundle\Table\Table;
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
     * @var CRUDControllerOptions
     */
    protected $crudOptions;

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

        // save the page number
        $this->setPage($page);

        // get the required classes for this action
        //        $repositoryClass = $this->crudOptions->getClass('repository');
        $repositoryClass = $this->definition->getClassRepository();
        //        $tableClass       = $this->crudOptions->getClass('table');
        $tableClass = $this->definition->getClassTable();

        // execute the required actions for this controller
        $repository = $this->getDoctrine()->getRepository($repositoryClass);
        $table      = new $tableClass();
        $table->setNavigator($this->get('navigator'), $this->definition->getKey());

        //        $entries    = $repository->getEntries($page, $this->crudOptions->getViewLimit());
        $entries = $repository->getEntries($page, $table->getPagination()->getLimit());

        //        $table->setRouter($this->get('router'));
        $table->getPagination()->setPage($page);
        $table->getPagination()->setCount($repository->getCount());
        $table->prepare($entries);

        // generate the view name
        $viewName = $this->getView('browse');

        // return the response
        return $this->render($viewName, array('table' => $table));
    }

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
        //        $repositoryClass = $this->crudOptions->getClass('repository');
        $repositoryClass = $this->definition->getClassRepository();
        //        $formClass       = $this->crudOptions->getClass('form');
        $formClass = $this->definition->getClassForm();

        // execute the required actions for this controller
        $repository = $this->getDoctrine()->getRepository($repositoryClass);
        $entity     = $repository->find($id);
        $form       = $this->createForm(new $formClass, $entity);

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
        //        $entityClass = $this->crudOptions->getClass('entity');
        $entityClass = $this->definition->getClassEntity();
        //        $formClass   = $this->crudOptions->getClass('form');
        $formClass = $this->definition->getClassForm();

        // execute the required actions for this controller
        $entity = new $entityClass();
        $form   = $this->createForm(new $formClass, $entity);
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
        //        $repositoryClass = $this->crudOptions->getClass('repository');
        $repositoryClass = $this->definition->getClassRepository();
        //        $formClass       = $this->crudOptions->getClass('form');
        $formClass = $this->definition->getClassForm();

        // execute the required actions for this controller
        $repository = $this->getDoctrine()->getRepository($repositoryClass);
        $entity     = $repository->find($id);
        $form       = $this->createForm(new $formClass, $entity);
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

        $this->crudOptions = new CRUDControllerOptions();
        $this->crudOptions->setAction($action);

        $this->loadDefinition();
        //        $this->initialiseCRUD();
        //        $this->crudOptions->check();

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

    /**
     *
     */
    protected abstract function initialiseCRUD();

    /**
     * @var Definition
     */
    protected $definition;

    /*************************************************************************
     * Generic Helper functions
     *************************************************************************/

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    private function redirectToBrowse()
    {

        $page = $this->getPage();
        //        $route = $this->crudOptions->getPrefix('route') . '_browse';
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
        $prefix          = $this->crudOptions->getPrefix('view');
        $prefixCommon    = 'ElektraSeedBundle::';
        //        $prefixCommon    = $this->crudOptions->getPrefix('viewCommon');

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

    /**
     * @return CRUDControllerOptions
     */
    protected function getOptions()
    {

        return $this->crudOptions;
    }

    /**
     * @return int
     */
    private function getPage()
    {

        return $this->get('session')->get($this->crudOptions->getPrefix('route') . '.page');
    }

    /**
     * @param int $page
     */
    private function setPage($page)
    {

        $this->get('session')->set($this->crudOptions->getPrefix('route') . '.page', $page);
    }
}