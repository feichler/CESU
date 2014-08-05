<?php
// TODO src: move forms to own FormType class
// TODO src: add CSRF Protection to the forms
namespace Elektra\SeedBundle\Controller;

use Elektra\SeedBundle\Entity\SeedUnit\SeedUnit;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SeedUnitController extends Controller
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

        $this->initializePage('Browse Seed Units');

        $repository = $this->getDoctrine()->getRepository('ElektraSeedBundle:SeedUnits\SeedUnits');
        // TODO src: add pagination to the list
        $units = $repository->findAll();

        return $this->render("ElektraSeedBundle:SeedUnits:list.html.twig", array('entries' => $units));
    }

    public function addAction(Request $request)
    {

        $this->initializePage('Add Seed Units');

        $unit = new SeedUnit();

        $formBuilder = $this->createFormBuilder($unit);
        $formBuilder->add('serialNumber', 'text');
        $formBuilder->add(
            'model',
            'entity',
            array(
                'class'       => 'ElektraSeedBundle:SeedUnits\Model',
                'property'    => 'name',
                'empty_value' => 'Choose a model',
                'required' => true,
            )
        );
        $formBuilder->add(
            'powerType',
            'entity',
            array(
                'class'    => 'ElektraSeedBundle:SeedUnits\PowerType',
                'property' => 'name',
            )
        );
        //        $formBuilder->add('description', 'textarea');
        $formBuilder->add('reset','reset');
        $formBuilder->add('save', 'submit');
//        $formBuilder->add('test','button');

        $form = $formBuilder->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($unit);
            $em->flush();

            $this->container->get('session')->getFlashBag()->add('success', ':Success: stored the new seed unit "' . $unit->getSerialNumber() . '"');

            return $this->redirect($this->generateUrl('_cesu_seed_units_list'));
        }

        return $this->render('ElektraSeedBundle:SeedUnits:form.html.twig', array('form' => $form->createView()));
    }

    public function editAction(Request $request, $id)
    {

        $this->initializePage('Edit Seed Units');

        $repository = $this->getDoctrine()->getRepository('ElektraSeedBundle:SeedUnits\SeedUnits');
        $unit       = $repository->find($id);

        $formBuilder = $this->createFormBuilder($unit);
        $formBuilder->add('serialNumber', 'text');
        $formBuilder->add(
            'model',
            'entity',
            array(
                'class'       => 'ElektraSeedBundle:SeedUnits\Model',
                'property'    => 'name',
                'empty_value' => 'Choose a model',
                'required' => true,
            )
        );
        $formBuilder->add(
            'powerType',
            'entity',
            array(
                'class'    => 'ElektraSeedBundle:SeedUnits\PowerType',
                'property' => 'name',
            )
        );
        //        $formBuilder->add('description', 'textarea');
        $formBuilder->add('save', 'submit');


        $form = $formBuilder->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->container->get('session')->getFlashBag()->add('success', ':Success: updated the seed unit "' . $unit->getSerialNumber() . '" - ID ' . $unit->getId());

            return $this->redirect($this->generateUrl('_cesu_seed_units_list'));
        }

        return $this->render('ElektraSeedBundle:SeedUnits:form.html.twig', array('form' => $form->createView()));
    }

    public function deleteAction(Request $request, $id)
    {

        // TODO src: check for references

        $repository = $this->getDoctrine()->getRepository('ElektraSeedBundle:SeedUnits\SeedUnits');
        $unit       = $repository->find($id);

        $this->getDoctrine()->getManager()->remove($unit);
        $this->getDoctrine()->getManager()->flush();

        $this->container->get('session')->getFlashBag()->add('success', ':Success: deleted the seed unit "' . $unit->getSerialNumber() . '" - ID ' . $unit->getId());

        return $this->redirect($this->generateUrl('_cesu_seed_units_list'));
    }
}