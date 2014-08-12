<?php

// TODO src: add localization strings

namespace Elektra\SeedBundle\Controller\SeedUnit;

use Elektra\SeedBundle\Controller\CRUDController;

//use Elektra\SeedBundle\Entity\SeedUnits\SeedUnitModel;
//use Elektra\SeedBundle\Form\Type\SeedUnits\SeedUnitModelType;
//use Elektra\SeedBundle\Table\SeedUnits\SeedUnitModelTable;
//use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//use Symfony\Component\HttpFoundation\Request;

class SeedUnitModelController extends CRUDController
{

//    public function __construct()
//    {
//
//        parent::__construct();
//
//        //        $this->setPageHeading('Seed Unit Models');
//        //        $this->setPageHeading('Seed Units - :action: Models');
//
//        //        $this->setRoutingPrefix('ElektraSeedBundle_seedunits_models');
//        //        $this->setThemeSection('Seed Unit Models', 'Seed Units', 'menu.master_data');
//    }

    /**
     * @return void
     */
    protected function initialiseVariables()
    {

        // set the prefixes
        $this->setPrefix('routing', 'ElektraSeedBundle_seedunits_models');
        $this->setPrefix('view', 'ElektraSiteBundle:Admin/SeedUnit/Models');

        // set the language keys
        $this->setLangKey('type', 'seedunit_models');
        $this->setLangKey('section', 'master_data');

        // set the classes
        $this->setClass('table', 'Elektra\SeedBundle\Table\SeedUnits\SeedUnitModelTable');
        $this->setClass('form', 'Elektra\SeedBundle\Form\Type\SeedUnits\SeedUnitModelType');
        $this->setClass('repository', 'ElektraSeedBundle:SeedUnits\SeedUnitModel');
        $this->setClass('entity', 'Elektra\SeedBundle\Entity\SeedUnits\SeedUnitModel');
    }




    //    protected $routePrefix = 'seedUnitModels_';

    //    public function browseAction(Request $request, $page)
    //    {
    //
    //        $this->setPage($page);
    //        $this->initSite('Seed Unit Models', 'Seed Units - Browse Models', 'menu.master_data');
    //
    //        $repository = $this->getDoctrine()->getRepository('ElektraSeedBundle:SeedUnits\SeedUnitModel');
    //        // TODO src: make 'perPage' a configurable parameter
    //        $entries = $repository->getEntries($page, 25);
    //
    //        $table = new SeedUnitModelTable($this->get('router'), $entries);
    //
    //        // TODO src: add pagination to the table
    //        return $this->render('ElektraSiteBundle:Admin/SeedUnit/Models:browse.html.twig', array('table' => $table));
    //    }

    //    public function viewAction(Request $request, $id)
    //    {
    //
    //        $this->initSite('Seed Unit Models', 'Seed Units - View Model', 'menu.master_data');
    //
    //        $model = $this->getModel($id);
    //    }

    //    public function addAction(Request $request)
    //    {
    //
    //        $this->initSite('Seed Unit Models', 'Seed Units - Add Model', 'menu.master_data');
    //
    //        $model = new SeedUnitModel();
    //
    //        $form = $this->createForm(new SeedUnitModelType(), $model);
    //        $form->handleRequest($request);
    //
    //        if ($form->isValid()) {
    //            if ($form->get('actions')->get('cancel')->isClicked()) {
    //                // Cancel button clicked -> return to browsing
    //                return $this->redirect($this->generateUrl($this->routePrefix . 'browse', array('page' => $this->getPage())));
    //            } else if ($form->get('actions')->get('save')->isClicked()) {
    //                // Save button clicked -> save the entry
    //                // TODO src: add validations
    //                //$errors = $form->getErrors();
    //                //                $validator = $this->get('validator');
    //
    //                //                $validator = $this->get('validator');
    //                //                $errors    = $validator->validate($model);
    //                //
    //                //                if (count($errors)) {
    //                //                    foreach ($errors as $error) {
    //                //                        $this->addMessage('error', $error);
    //                //                    }
    //                //                }
    //
    //                $em = $this->getDoctrine()->getManager();
    //                $em->persist($model);
    //                $em->flush();
    //
    //                $this->addMessage('success', ' New model successfully saved.');
    //
    //                return $this->redirect($this->generateUrl($this->routePrefix . 'browse', array('page' => $this->getPage())));
    //            }
    //        }
    //
    //        $view = $form->createView();
    //
    //        return $this->render('ElektraSiteBundle:Admin/SeedUnit/Models:form.html.twig', array('form' => $view));
    //    }

    //    public function editAction(Request $request, $id)
    //    {
    //
    //        $this->initSite('Seed Unit Models', 'Seed Units - Edit Model', 'menu.master_data');
    //
    //        $model = $this->getModel($id);
    //
    //        $form = $this->createForm(new SeedUnitModelType(), $model);
    //        $form->handleRequest($request);
    //
    //        if ($form->isValid()) {
    //            if ($form->get('actions')->get('cancel')->isClicked()) {
    //                // Cancel button clicked -> return to browsing
    //                return $this->redirect($this->generateUrl($this->routePrefix . 'browse', array('page' => $this->getPage())));
    //            } else if ($form->get('actions')->get('save')->isClicked()) {
    //                $this->getDoctrine()->getManager()->flush();
    //
    //                $this->addMessage('success', 'Model successfully updated.');
    //
    //                return $this->redirect($this->generateUrl($this->routePrefix . 'browse', array('page' => $this->getPage())));
    //            }
    //        }
    //
    //        $view = $form->createView();
    //
    //        return $this->render('ElektraSiteBundle:Admin/SeedUnit/Models:form.html.twig', array('form' => $view));
    //    }

    //    public function deleteAction(Request $request, $id)
    //    {
    //
    //        $model = $this->getModel($id);
    //
    //        // TODO src: check if model can be deleted (no reference present)
    //
    //        $manager = $this->getDoctrine()->getManager();
    //        $manager->remove($model);
    //        $manager->flush();
    //
    //        $this->addMessage('success', 'Model successfully deleted.');
    //
    //        return $this->redirect($this->generateUrl($this->routePrefix . 'browse', array('page' => $this->getPage())));
    //    }

    //    private function initSite($title, $heading, $section = '')
    //    {
    //
    //        $site = $this->container->get('site');
    //        $site->initializeAdminPage($title, $heading, $section);
    //    }
    //
    //    private function setPage($page)
    //    {
    //
    //        $this->get('session')->set($this->routePrefix . '.page', $page);
    //    }

    //    private function getPage()
    //    {
    //
    //        return $this->get('session')->get($this->routePrefix . '.page');
    //    }
    //
    //    private function addMessage($type, $msg)
    //    {
    //
    //        $this->get('session')->getFlashBag()->add($type, $msg);
    //    }

    //    private function getModel($id)
    //    {
    //
    //        $repository = $this->getDoctrine()->getRepository('ElektraSeedBundle:SeedUnits\SeedUnitModel');
    //        $model      = $repository->find($id);
    //
    //        return $model;
    //    }

}