<?php
// TODO src: move forms to own FormType class
// TODO src: add CSRF Protection to the forms
namespace Elektra\SeedBundle\Controller\SeedUnit;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Elektra\SeedBundle\Entity\SeedUnits\SeedUnitModel;

class ModelController extends Controller
{

    protected function initializePage($title)
    {

        $this->container->get('elektra.menu')->initialize();

        $page = $this->container->get('theme.page');
        $page->setBundle('ElektraSiteBundle');
        $page->includeEverything();
        $page->setHeading($title);
        $page->setBodyId('seedunit-models');
    }

    public function listAction(Request $request, $page)
    {

        $this->initializePage('Browse Seed Unit Models');

        $repository = $this->getDoctrine()->getRepository('ElektraSeedBundle:SeedUnits\Model');
        // TODO src: add pagination to the list
        $models = $repository->findAll();

        // TODO src: also display number of seed units associated with this type

        return $this->render("ElektraSeedBundle:SeedUnits/Model:list.html.twig", array('entries' => $models));
    }

    public function addAction(Request $request)
    {

        $this->initializePage('Add Seed Unit Models');

        $model = new SeedUnitModel();

        $formBuilder = $this->createFormBuilder($model);
        $formBuilder->add('name', 'text');
        $formBuilder->add('description', 'textarea');
        $formBuilder->add('save', 'submit');

        $form = $formBuilder->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($model);
            $em->flush();

            $this->container->get('session')->getFlashBag()->add('success', ':Success: stored the new model "' . $model->getName() . '"');

            return $this->redirect($this->generateUrl('_cesu_seed_unit_models_list'));
        }

        return $this->render('ElektraSeedBundle:SeedUnits/Model:form.html.twig', array('form' => $form->createView()));
    }

    public function editAction(Request $request, $id)
    {

        $this->initializePage('Edit Seed Unit Models');

        $repository = $this->getDoctrine()->getRepository('ElektraSeedBundle:SeedUnits\Model');
        $model      = $repository->find($id);

        $formBuilder = $this->createFormBuilder($model);
        $formBuilder->add('name', 'text');
        $formBuilder->add('description', 'textarea');
        $formBuilder->add('save', 'submit');

        $form = $formBuilder->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->container->get('session')->getFlashBag()->add('success', ':Success: updated the model "' . $model->getName() . '" - ID ' . $model->getModelId());

            return $this->redirect($this->generateUrl('_cesu_seed_unit_models_list'));
        }

        return $this->render('ElektraSeedBundle:SeedUnits/Model:form.html.twig', array('form' => $form->createView()));
    }

    public function deleteAction(Request $request, $id)
    {

        // TODO src: check for references

        $repository = $this->getDoctrine()->getRepository('ElektraSeedBundle:SeedUnits\Model');
        $model      = $repository->find($id);

        if (!$model->getCanDelete()) {
            $this->container->get('session')->getFlashBag()->add('error', ':Not possible: the model "' . $model->getName() . '" - ID ' . $model->getId() . ' cannot be deleted (seed units associated)');

            return $this->redirect($this->generateUrl('_cesu_seed_unit_models_list'));
        }


        $this->getDoctrine()->getManager()->remove($model);
        $this->getDoctrine()->getManager()->flush();

        $this->container->get('session')->getFlashBag()->add('success', ':Success: deleted the model "' . $model->getName() . '" - ID ' . $model->getModelId());

        return $this->redirect($this->generateUrl('_cesu_seed_unit_models_list'));
    }
}