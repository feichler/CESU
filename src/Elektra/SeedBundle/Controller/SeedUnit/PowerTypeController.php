<?php
// TODO src: move forms to own FormType class
// TODO src: add CSRF Protection to the forms
namespace Elektra\SeedBundle\Controller\SeedUnit;

use Elektra\SeedBundle\Entity\SeedUnit\PowerType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PowerTypeController extends Controller
{

    protected function initializePage($title)
    {

        $this->container->get('elektra.menu')->initialize();

        $page = $this->container->get('theme.page');
        $page->setBundle('ElektraSiteBundle');
        $page->includeEverything();
        $page->setHeading($title);
    }

    public function listAction(Request $request, $page)
    {

        $this->initializePage('Browse Seed Unit Power Types');

        $repository = $this->getDoctrine()->getRepository('ElektraSeedBundle:SeedUnit\PowerType');
        // TODO src: add pagination to the list
        $powerTypes = $repository->findAll();

        // TODO src: also display number of seed units associated with this power type

        return $this->render("ElektraSeedBundle:SeedUnit/PowerType:list.html.twig", array('entries' => $powerTypes));
    }

    public function addAction(Request $request)
    {

        $this->initializePage('Add Seed Unit Power Types');

        $powerType = new PowerType();

        $formBuilder = $this->createFormBuilder($powerType);
        $formBuilder->add('name', 'text');
        $formBuilder->add('description', 'textarea');
        $formBuilder->add('save', 'submit');

        $form = $formBuilder->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($powerType);
            $em->flush();

            $this->container->get('session')->getFlashBag()->add('success', ':Success: stored the new power type "' . $powerType->getName() . '"');

            return $this->redirect($this->generateUrl('_cesu_seed_unit_power_types_list'));
        }

        return $this->render('ElektraSeedBundle:SeedUnit/PowerType:form.html.twig', array('form' => $form->createView()));
    }

    public function editAction(Request $request, $id)
    {

        $this->initializePage('Edit Seed Unit Power Types');

        $repository = $this->getDoctrine()->getRepository('ElektraSeedBundle:SeedUnit\PowerType');
        $powerType  = $repository->find($id);

        $formBuilder = $this->createFormBuilder($powerType);
        $formBuilder->add('name', 'text');
        $formBuilder->add('description', 'textarea');
        $formBuilder->add('save', 'submit');

        $form = $formBuilder->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->container->get('session')->getFlashBag()->add('success', ':Success: updated the power type "' . $powerType->getName() . '" - ID ' . $powerType->getPowerTypeId());

            return $this->redirect($this->generateUrl('_cesu_seed_unit_power_types_list'));
        }

        return $this->render('ElektraSeedBundle:SeedUnit/PowerType:form.html.twig', array('form' => $form->createView()));
    }

    public function deleteAction(Request $request, $id)
    {

        // TODO src: check for references

        $repository = $this->getDoctrine()->getRepository('ElektraSeedBundle:SeedUnit\PowerType');
        $powerType  = $repository->find($id);

        $this->getDoctrine()->getManager()->remove($powerType);
        $this->getDoctrine()->getManager()->flush();

        $this->container->get('session')->getFlashBag()->add('success', ':Success: deleted the power type "' . $powerType->getName() . '" - ID ' . $powerType->getPowerTypeId());

        return $this->redirect($this->generateUrl('_cesu_seed_unit_power_types_list'));
    }
}