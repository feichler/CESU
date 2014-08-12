<?php

namespace Elektra\SeedBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class CRUDController extends Controller
{

    /**
     * prefixes for routing and views
     *
     * @var array
     */
    protected $prefixes = array(
        'routing' => '',
        'view'    => '',
    );

    /**
     * language specific strings for rendering
     *
     * @var array
     */
    protected $languageStrings = array(
        'title'      => '',
        'section'    => '',
        'entityName' => '',
    );

    /**
     * classes used by this CRUD controller
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
     *
     * @var int
     */
    // TODO src: make per page a parameter
    protected $perPage = 25;

    /**
     * Set a specific class to be used by this controller
     *
     * @param string $type
     * @param string $class
     */
    protected function setClass($type, $class)
    {

        $this->classes[$type] = $class;
    }

    /**
     * Set a specific language string
     *
     * @param string $type
     * @param string $string
     */
    protected function setLanguageString($type, $string)
    {

        $this->languageStrings[$type] = $string;
    }

    /**
     * Set a specific prefix
     *
     * @param string $type
     * @param string $prefix
     */
    protected function setPrefix($type, $prefix)
    {

        $this->prefixes[$type] = $prefix;
    }

    /**
     * @param Request $request
     * @param         $page
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function browseAction(Request $request, $page)
    {

        $this->get('session')->set($this->prefixes['routing'] . '.page', $page);
        $this->prepareTheme('Browse');
        /*
         * Generic:
         *      - get entries to display
         *      - render the view
         */

        $repositoryClass = $this->classes['repository'];
        $repository      = $this->getDoctrine()->getRepository($repositoryClass);

        $entries = $repository->getEntries($page, $this->perPage);

        $tableClass = $this->classes['table'];
        $table      = new $tableClass($this->get('router'), $entries);

        // TODO src add pagination to the table or the view
        return $this->render($this->prefixes['view'] . ':browse.html.twig', array('table' => $table));
    }

    /**
     * @param Request $request
     * @param         $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction(Request $request, $id)
    {

        $this->prepareTheme('View');

        // TODO implement method

        return new Response('VIEW ' . $id);
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function addAction(Request $request)
    {

        $this->prepareTheme('Add');

        $entityClass = $this->classes['entity'];
        $entity      = new $entityClass();

        $formClass = $this->classes['form'];
        $form      = $this->createForm(new $formClass(), $entity);
        $form->handleRequest($request);

        if ($form->isValid() || $form->get('actions')->get('cancel')->isClicked()) {

            if ($form->get('actions')->get('save')->isClicked()) {
                // Save Button has been clicked -> store entered data

                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();

                $this->addMessage('success', 'New ' . $this->languageStrings['entityName'] . ' successfully added');
            } else {
                // Usually this means, the "Cancel" Button has been clicked, but in also every other case -> discard the entered data and redirect
            }

            return $this->redirectToBrowse();
        }

        $view = $form->createView();

        return $this->render($this->prefixes['view'] . ':form.html.twig', array('form' => $view));
    }

    /**
     * @param Request $request
     * @param         $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editAction(Request $request, $id)
    {

        $this->prepareTheme('Edit');

        $repositoryClass = $this->classes['repository'];
        $repository      = $this->getDoctrine()->getRepository($repositoryClass);

        $entity = $repository->find($id);

        $formClass = $this->classes['form'];
        $form      = $this->createForm(new $formClass(), $entity);
        $form->handleRequest($request);

        if ($form->isValid() || $form->get('actions')->get('cancel')->isClicked()) {
            if ($form->get('actions')->get('save')->isClicked()) {
                // Save Button has been clicked -> store entered data

                $this->getDoctrine()->getManager()->flush();

                $this->addMessage('success', $this->languageStrings['entityName'] . ' successfully updated');
            } else {
                // Usually this means, the "Cancel" Button has been clicked, but in also every other case -> discard the entered data and redirect
            }

            return $this->redirectToBrowse();
        }

        $view = $form->createView();

        return $this->render($this->prefixes['view'] . ':form.html.twig', array('form' => $view));
    }

    /**
     * @param Request $request
     * @param         $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, $id)
    {

        $repositoryClass = $this->classes['repository'];
        $repository      = $this->getDoctrine()->getRepository($repositoryClass);

        $entity = $repository->find($id);

        // TODO src: add checks if the entity can be deleted (references, privileges, etc)

        $manager = $this->getDoctrine()->getManager();
        $manager->remove($entity);
        $manager->flush();

        $this->addMessage('success', $this->languageStrings['entityName'] . ' successfully deleted');

        return $this->redirectToBrowse();
    }

    /**
     * @param $heading
     */
    protected function prepareTheme($heading)
    {

        $site = $this->get('site');
        $site->initializeAdminPage($this->languageStrings['title'], $heading . ' ' . $this->languageStrings['title'], $this->languageStrings['section']);
    }

    /**
     * @return mixed
     */
    protected function getPage()
    {

        return $this->get('session')->get($this->prefixes['routing'] . '.page');
    }

    /**
     * @param $type
     * @param $message
     */
    protected function addMessage($type, $message)
    {

        $this->get('session')->getFlashBag()->add($type, $message);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectToBrowse()
    {

        return $this->redirect($this->generateUrl($this->prefixes['routing'] . '_browse', array('page' => $this->getPage())));
    }
}