<?php
// TODO src: move forms to own FormType class
// TODO src: add CSRF Protection to the forms
namespace Elektra\SeedBundle\Controller\SeedUnit;

use Elektra\SeedBundle\Controller\CRUDController;

class SeedUnitPowerCordTypeController extends CRUDController
{

    /**
     * @return void
     */
    protected function initialiseVariables()
    {

        // set the prefixes
        $this->setPrefix('routing', 'ElektraSeedBundle_seedunits_powerCordTypes');
        $this->setPrefix('view', 'ElektraSeedBundle:SeedUnit/SeedUnitPowerCordTypes');

        // set the language keys
        $this->setLangKey('type', 'seedunit_powercordtypes');
        $this->setLangKey('section', 'master_data');

        // set the classes
        $this->setClass('table', 'Elektra\SeedBundle\Table\SeedUnits\SeedUnitPowerCordTypeTable');
        $this->setClass('form', 'Elektra\SeedBundle\Form\Type\SeedUnits\SeedUnitPowerCordTypeType');
        $this->setClass('repository', 'ElektraSeedBundle:SeedUnits\SeedUnitPowerCordType');
        $this->setClass('entity', 'Elektra\SeedBundle\Entity\SeedUnits\SeedUnitPowerCordType');
    }



    //    public function browseAction(Request $request, $page)
    //    {
    //
    //        $site = $this->container->get('site');
    //        $site->initializeAdminPage('Seed Unit Power Cord Types', 'Seed Units - Browser Power Cord Types', 'menu.master_data');
    //
    //        return $this->render('ElektraSiteBundle:Admin/SeedUnit/PowerCordTypes:browse.html.twig');
    //    }
    //
    //    protected function initializePage($title)
    //    {
    //
    //        $this->container->get('elektra.menu')->initialize();
    //
    //        $page = $this->container->get('theme.page');
    //        $page->setBundle('ElektraSiteBundle');
    //        $page->includeEverything();
    //        $page->setHeading($title);
    //    }
    //
    //    public function listAction(Request $request, $page)
    //    {
    //
    //        $this->initializePage('Browse Seed Unit Power Types');
    //
    //        $repository = $this->getDoctrine()->getRepository('ElektraSeedBundle:SeedUnits\SeedUnitPowerTypes');
    //        // TODO src: add pagination to the list
    //        $powerTypes = $repository->findAll();
    //
    //        // TODO src: also display number of seed units associated with this power type
    //
    //        return $this->render("ElektraSeedBundle:SeedUnits/SeedUnitPowerTypes:browse.html.twig", array('entries' => $powerTypes));
    //    }
    //
    //    public function addAction(Request $request)
    //    {
    //
    //        $this->initializePage('Add Seed Unit Power Types');
    //
    //        $powerType = new SeedUnitPowerCordType();
    //
    //        $formBuilder = $this->createFormBuilder($powerType);
    //        $formBuilder->add('name', 'text');
    //        $formBuilder->add('description', 'textarea');
    //        $formBuilder->add('save', 'submit');
    //
    //        $form = $formBuilder->getForm();
    //
    //        $form->handleRequest($request);
    //
    //        if ($form->isValid()) {
    //            $em = $this->getDoctrine()->getManager();
    //            $em->persist($powerType);
    //            $em->flush();
    //
    //            $this->container->get('session')->getFlashBag()->add('success', ':Success: stored the new power type "' . $powerType->getName() . '"');
    //
    //            return $this->redirect($this->generateUrl('_cesu_seed_unit_power_types_list'));
    //        }
    //
    //        return $this->render('ElektraSeedBundle:SeedUnits/SeedUnitPowerTypes:form.html.twig', array('form' => $form->createView()));
    //    }
    //
    //    public function editAction(Request $request, $id)
    //    {
    //
    //        $this->initializePage('Edit Seed Unit Power Types');
    //
    //        $repository = $this->getDoctrine()->getRepository('ElektraSeedBundle:SeedUnits\SeedUnitPowerTypes');
    //        $powerType  = $repository->find($id);
    //
    //        $formBuilder = $this->createFormBuilder($powerType);
    //        $formBuilder->add('name', 'text');
    //        $formBuilder->add('description', 'textarea');
    //        $formBuilder->add('save', 'submit');
    //
    //        $form = $formBuilder->getForm();
    //
    //        $form->handleRequest($request);
    //
    //        if ($form->isValid()) {
    //            $em = $this->getDoctrine()->getManager();
    //            $em->flush();
    //
    //            $this->container->get('session')->getFlashBag()->add('success', ':Success: updated the power type "' . $powerType->getName() . '" - ID ' . $powerType->getPowerTypeId());
    //
    //            return $this->redirect($this->generateUrl('_cesu_seed_unit_power_types_list'));
    //        }
    //
    //        return $this->render('ElektraSeedBundle:SeedUnits/SeedUnitPowerTypes:form.html.twig', array('form' => $form->createView()));
    //    }
    //
    //    public function deleteAction(Request $request, $id)
    //    {
    //
    //        // TODO src: check for references
    //
    //        $repository = $this->getDoctrine()->getRepository('ElektraSeedBundle:SeedUnits\SeedUnitPowerTypes');
    //        $powerType  = $repository->find($id);
    //
    //        if (!$powerType->getCanDelete()) {
    //            $this->container->get('session')->getFlashBag()->add(
    //                'error',
    //                ':Not possible: the power type "' . $powerType->getName() . '" - ID ' . $powerType->getPowerTypeId() . ' cannot be deleted (seed units associated)'
    //            );
    //
    //            return $this->redirect($this->generateUrl('_cesu_seed_unit_power_types_list'));
    //        }
    //
    //        $this->getDoctrine()->getManager()->remove($powerType);
    //        $this->getDoctrine()->getManager()->flush();
    //
    //        $this->container->get('session')->getFlashBag()->add('success', ':Success: deleted the power type "' . $powerType->getName() . '" - ID ' . $powerType->getPowerTypeId());
    //
    //        return $this->redirect($this->generateUrl('_cesu_seed_unit_power_types_list'));
    //    }
}