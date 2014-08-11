<?php

// TODO src: add localization strings

namespace Elektra\SeedBundle\Controller\SeedUnit;

use Elektra\SeedBundle\Entity\SeedUnits\SeedUnitModel;
use Elektra\SeedBundle\Form\Type\SeedUnits\SeedUnitModelType;
use Elektra\SeedBundle\Table\SeedUnits\SeedUnitModelTable;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SeedUnitModelController extends Controller
{

    protected $routePrefix = 'seedUnitModels_';

    public function browseAction(Request $request, $page)
    {

        $this->setPage($page);
        $this->initSite('Seed Unit Models', 'Seed Units - Browse Models', 'menu.master_data');

        $repository = $this->getDoctrine()->getRepository('ElektraSeedBundle:SeedUnits\SeedUnitModel');
        // TODO src: make 'perPage' a configurable parameter
        $entries = $repository->getEntries($page, 25);

        $table = new SeedUnitModelTable($this->get('router'), $entries);

        // TODO src: add pagination to the table
        return $this->render('ElektraSiteBundle:Admin/SeedUnit/Models:browse.html.twig', array('table' => $table));
    }

    public function viewAction(Request $request, $id)
    {

        $this->initSite('Seed Unit Models', 'Seed Units - View Model', 'menu.master_data');
    }

    public function addAction(Request $request)
    {

        $this->initSite('Seed Unit Models', 'Seed Units - Add Model', 'menu.master_data');

        $model = new SeedUnitModel();

        $form = $this->createForm(new SeedUnitModelType(), $model);
        $form->handleRequest($request);

        if ($form->isValid()) {
            if ($form->get('actions')->get('cancel')->isClicked()) {
                // Cancel button clicked -> return to browsing
                return $this->redirect($this->generateUrl($this->routePrefix . 'browse', array('page' => $this->getPage())));
            } else if ($form->get('actions')->get('save')->isClicked()) {
                // Save button clicked -> save the entry
                // TODO src: add validations
                $em = $this->getDoctrine()->getManager();
                $em->persist($model);
                $em->flush();

                $this->addMessage('success', ' New model successfully saved.');

                return $this->redirect($this->generateUrl($this->routePrefix . 'browse', array('page' => $this->getPage())));
            }
        }

        $view = $form->createView();

        return $this->render('ElektraSiteBundle:Admin/SeedUnit/Models:form.html.twig', array('form' => $view));
    }

    public function editAction(Request $request, $id)
    {

        $this->initSite('Seed Unit Models', 'Seed Units - Edit Model', 'menu.master_data');

        $repository = $this->getDoctrine()->getRepository('ElektraSeedBundle:SeedUnits\SeedUnitModel');
        $model      = $repository->find($id);

        $form = $this->createForm(new SeedUnitModelType(), $model);
        $form->handleRequest($request);

        if ($form->isValid()) {
            if ($form->get('actions')->get('cancel')->isClicked()) {
                // Cancel button clicked -> return to browsing
                return $this->redirect($this->generateUrl($this->routePrefix . 'browse', array('page' => $this->getPage())));
            } else if ($form->get('actions')->get('save')->isClicked()) {
                $this->getDoctrine()->getManager()->flush();

                $this->addMessage('success', 'Model successfully updated.');

                return $this->redirect($this->generateUrl($this->routePrefix . 'browse', array('page' => $this->getPage())));
            }
        }

        $view = $form->createView();

        return $this->render('ElektraSiteBundle:Admin/SeedUnit/Models:form.html.twig', array('form' => $view));
    }

    public function deleteAction(Request $request, $id)
    {
    }

    private function initSite($title, $heading, $section = '')
    {

        $site = $this->container->get('site');
        $site->initializeAdminPage($title, $heading, $section);
    }

    private function setPage($page)
    {

        $this->get('session')->set($this->routePrefix . '.page', $page);
    }

    private function getPage()
    {

        return $this->get('session')->get($this->routePrefix . '.page');
    }

    private function addMessage($type, $msg)
    {

        $this->get('session')->getFlashBag()->add($type, $msg);
    }
}