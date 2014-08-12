<?php

namespace Elektra\SeedBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class CRUDController extends Controller
{

    /**
     * Prefixes for
     *      - routing
     *      - views
     *
     * @var array
     */
    protected $prefixes = array(
        'routing' => '',
        'view'    => '',
    );

    /**
     * language keys for
     *      - type (entity type)
     *      - section (for heading translation)
     *
     * @var array
     */
    protected $langKeys = array(
        'type'    => '',
        'section' => '',
    );

    /**
     * classes used for the CRUD actions
     *
     * @var array
     */
    protected $classes = array(
        'entity'     => '',
        'table'      => '',
        'form'       => '',
        'repository' => '',
    );

    /**
     * @var int
     */
    // TODO src: make "per-page" display a parameter
    protected $perPage = 25;

    /*************************************************************************
     * Setters to configure this CRUD controller
     *************************************************************************/

    /**
     * @param $type
     * @param $class
     *
     * @throws \InvalidArgumentException
     */
    protected function setClass($type, $class)
    {

        if (!array_key_exists($type, $this->classes)) {
            throw new \InvalidArgumentException('Unknown class type "' . $type . '"');
        }

        $this->classes[$type] = $class;
    }

    /**
     * @param $type
     * @param $key
     *
     * @throws \InvalidArgumentException
     */
    protected function setLangKey($type, $key)
    {

        if (!array_key_exists($type, $this->langKeys)) {
            throw new \InvalidArgumentException('Unknown lang key type "' . $type . '"');
        }

        $this->langKeys[$type] = $key;
    }

    /**
     * @param $type
     * @param $prefix
     *
     * @throws \InvalidArgumentException
     */
    protected function setPrefix($type, $prefix)
    {

        if (!array_key_exists($type, $this->prefixes)) {
            throw new \InvalidArgumentException('Unknown prefix type "' . $type . '"');
        }

        $this->prefixes[$type] = $prefix;
    }

    /*************************************************************************
     * Generic Helper functions
     *************************************************************************/

    /**
     * Initialise the controller
     *
     * @param string $action
     *
     * @throws \BadMethodCallException
     */
    private function initialise($action = '')
    {

        // let the inheriting controller set the required variables
        $this->initialiseVariables();

        // Check if all required values have been set by the last call
        foreach ($this->prefixes as $type => $prefix) {
            if ($prefix === '') {
                throw new \BadMethodCallException('Initialisation not complete - prefix "' . $type . '" is missing');
            }
        }

        foreach ($this->langKeys as $type => $key) {
            if ($key === '') {
                throw new \BadMethodCallException('Initialisation not complete - language key "' . $type . '" is missing');
            }
        }

        foreach ($this->classes as $type => $class) {
            if ($class === '') {
                throw new \BadMethodCallException('Initialisation not complete - class "' . $type . '" is missing');
            }
        }

        if ($action != '') { // usually only for the deleteAction no theme is required as this action redirects
            // initialise the theme
            $theme = $this->get('site');
            $theme->initializeCRUDPage('admin', $this->langKeys['type'], $this->langKeys['section'], $action);
        }
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    private function redirectToBrowse()
    {

        $page  = $this->get('session')->get($this->prefixes['routing'] . '.page');
        $route = $this->prefixes['routing'] . '_browse';

        return $this->redirect($this->generateUrl($route, array('page' => $page)));
    }

    /*************************************************************************
     * Message Helper functions
     *************************************************************************/

    /**
     * @param string      $action
     * @param int|null    $id
     * @param string|null $name
     */
    private function addSuccessMessage($action, $id = null, $name = null)
    {

        $this->addActionMessage('success', $action, $id, $name);
    }

    /**
     * @param string      $action
     * @param int|null    $id
     * @param string|null $name
     */
    private function addErrorMessage($action, $id = null, $name = null)
    {

        $this->addActionMessage('error', $action, $id, $name);
    }

    /**
     * @param string      $type
     * @param string      $action
     * @param int|null    $id
     * @param string|null $name
     */
    private function addActionMessage($type, $action, $id = null, $name = null)
    {

        $translator = $this->get('translator');

        $replacements = array(
            '%type%' => $translator->transChoice('site.admin.entities.' . $this->langKeys['type'] . '.type', 1)
        );
        if ($id !== null) {
            $replacements['%id%'] = $id;
        }
        if ($name !== null) {
            $replacements['%name%'] = $name;
        }

        $message = $translator->trans('site.admin.messages.' . $type . '.' . $action, $replacements);

        $this->addMessage($type, $message);
    }

    private function addMessage($type, $message)
    {

        $this->get('session')->getFlashBag()->add($type, $message);
    }



    /*************************************************************************
     * Now the actions themselves follow
     *************************************************************************/

    /**
     * @param Request $request
     * @param int     $page
     *
     * @return Response
     */
    public function browseAction(Request $request, $page)
    {

        $this->initialise('browse');

        // save the page into the session for returning to the list view
        $this->get('session')->set($this->prefixes['routing'] . '.page', $page);

        $repositoryClass = $this->classes['repository'];
        $tableClass      = $this->classes['table'];

        $repository = $this->getDoctrine()->getRepository($repositoryClass);
        $entries    = $repository->getEntries($page, $this->perPage);
        $table      = new $tableClass($this->get('router'), $entries);

        // TODO src implement pagination and add to the table / view
        $viewName = $this->prefixes['view'] . ':browse.html.twig';

        return $this->render($viewName, array('table' => $table));
    }

    /**
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    public function viewAction(Request $request, $id)
    {

        $this->initialise('view');

        return new Response();
    }

    public function addAction(Request $request)
    {

        $this->initialise('add');

        $entityClass = $this->classes['entity'];
        $formClass   = $this->classes['form'];

        $entity = new $entityClass();
        $form   = $this->createForm(new $formClass(), $entity);
        $form->handleRequest($request);

        if ($form->isValid() && $form->get('actions')->get('save')->isClicked()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->addSuccessMessage('add', $entity->getId());

            return $this->redirectToBrowse();
        } else if ($form->get('actions')->get('cancel')->isClicked()) {
            // on "cancel" event, discard anything and redirect to the browse page
            return $this->redirectToBrowse();
        }

        $view     = $form->createView();
        $viewName = $this->prefixes['view'] . ':form.html.twig';

        return $this->render($viewName, array('form' => $view));
    }

    public function editAction(Request $request, $id)
    {

        $this->initialise('edit');

        $repositoryClass = $this->classes['repository'];
        $formClass       = $this->classes['form'];

        $repository = $this->getDoctrine()->getRepository($repositoryClass);
        $entity     = $repository->find($id);
        $form       = $this->createForm(new $formClass(), $entity);
        $form->handleRequest($request);

        if ($form->isValid() && $form->get('actions')->get('save')->isClicked()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addSuccessMessage('edit', $entity->getId());

            return $this->redirectToBrowse();
        } else if ($form->get('actions')->get('cancel')->isClicked()) {
            // on "cancel" event, discard anything and redirect to the browse page
            return $this->redirectToBrowse();
        }

        $view     = $form->createView();
        $viewName = $this->prefixes['view'] . ':form.html.twig';

        return $this->render($viewName, array('form' => $view));
    }

    public function deleteAction(Request $request, $id)
    {

        $this->initialise();

        $repositoryClass = $this->classes['repository'];

        $repository = $this->getDoctrine()->getRepository($repositoryClass);
        $entity     = $repository->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($entity);
        $em->flush();

        $this->addSuccessMessage('delete');

        return $this->redirectToBrowse();
    }

    /*************************************************************************
     * initialisation methods to be implemented by the inheriting controller
     *************************************************************************/
    /**
     * @return void
     */
    protected abstract function initialiseVariables();
}