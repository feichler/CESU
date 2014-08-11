<?php
// TODO src: move forms to own FormType class
// TODO src: add CSRF Protection to the forms
namespace Elektra\SeedBundle\Controller\SeedUnit;

use Elektra\SeedBundle\Entity\Auditing\Audit;
use Elektra\SeedBundle\Form\Type\SeedUnits\SeedUnitModelType;
use Elektra\SeedBundle\Table\SeedUnits\SeedUnitModelTable;
use Elektra\ThemeBundle\Element\Table;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Elektra\SeedBundle\Entity\SeedUnits\SeedUnitModel;

class ModelController extends Controller
{

    public function browseAction(Request $request, $page)
    {

        $this->get('session')->set('seedUnitModels_browse.page', $page);

        $site = $this->container->get('site');
        $site->initializeAdminPage('Seed Unit Models', 'Seed Units - Browse Models', 'menu.master_data');

        $repository = $this->getDoctrine()->getRepository('ElektraSeedBundle:SeedUnits\SeedUnitModel');
        $entries    = $repository->getEntries($page, 25);

        $table = new SeedUnitModelTable($this->get('router'), $entries);

        //        $table = new Table();
        //        $table->defaultStyling();
        //
        //        $header   = $table->addHeaderRow();
        //        $headerId = $header->addCell();
        //        $headerId->addHTMLContent('ID');
        //        $headerId->setWidth('5', '%');
        //        $headerModel = $header->addCell();
        //        $headerModel->addHTMLContent('Model');
        //        $headerAudit = $header->addCell();
        //        $headerAudit->addHTMLContent('Created / Modified');
        //        $headerActions = $header->addCell();
        //        $headerActions->addHTMLContent('Actions');
        //
        //        $footer     = $table->addFooterRow();
        //        $footerCell = $footer->addCell();
        //        $footerCell->setColumnSpan(4);
        //        $addLink = $this->get('router')->generate('seedUnitModels_add');
        //        $footerCell->addClass('text-right');
        //        $footerCell->addActionContent('add', array($addLink));

        //        var_dump($entries);
        // TODO src: add pagination to the list

        //        $theme = $this->initializeTheme();
        //        $theme->setPageVar('navbar.brand.name', 'CESU Admin');
        //        $theme->setPageVar('navbar.brand.route', 'admin_home');
        //        $theme->setPageVar('title', 'Seed Unit Models - CESU Administration');
        //        $theme->setPageVar('heading', 'Browse Seed Unit Models');
        //
        //        $theme->setSubTemplate('navbar', 'ElektraSiteBundle:Parts/Navigation:admin-navbar.html.twig');
        //        $theme->setSubTemplate('footer', 'ElektraSiteBundle:Parts/Footer:admin-footer.html.twig');

        return $this->render('ElektraSiteBundle:Admin/SeedUnit/Models:browse.html.twig', array('table' => $table));
    }

    public function viewAction(Request $request, $id)
    {
        // TODO src implement action
    }

    public function addAction(Request $request)
    {

        $site = $this->container->get('site');
        $site->initializeAdminPage('Seed Unit Models', 'Seed Units - Add Model', 'menu.master_data');

        $model = new SeedUnitModel();
        $form  = $this->createForm(new SeedUnitModelType(), $model);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $lastPage = $this->get('session')->get('seedUnitModels_browse.page');
            if ($form->get('actions')->get('cancel')->isClicked()) {
                return $this->redirect($this->generateUrl('seedUnitModels_browse', array('page' => $lastPage)));
            } else if ($form->get('actions')->get('save')->isClicked()) {
                $em    = $this->getDoctrine()->getManager();
                $audit = new Audit();
                $audit->setCreatedBy($this->getUser());
                $audit->setCreatedAt(time());
                $model->setAudit($audit);
                $em->persist($model);
                $em->flush();

                $this->get('session')->getFlashBag()->add('success', 'New Model successfully saved. Stored ID ' . $model->getId());

                return $this->redirect($this->generateUrl('seedUnitModels_browse', array('page' => $lastPage)));
            }
        }

        $view = $form->createView();
        //        $view->offsetExists('a');
        //        echo get_class($form);
        //        echo '<br />';
        //        echo get_class($view);
        //$view->vars['asdf']='TESTING';
        //$view->vars['attr']['test'] = 'asdf';
        //        echo '<br />';
        //        echo '<pre>';
        //        print_r($view->vars['test']);
        //        echo '</pre>';

        return $this->render('ElektraSiteBundle:Admin/SeedUnit/Models:form.html.twig', array('form' => $form->createView()));
    }

    public function editAction(Request $request, $id)
    {
        // TODO src implement action
    }

    public function deleteAction(Request $request, $id)
    {

        // TODO src: check if the entry can be deleted (privileges, references)

        $repository = $this->getDoctrine()->getRepository('ElektraSeedBundle:SeedUnits\SeedUnitModel');
        $model      = $repository->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($model);
        $em->flush();

        $this->container->get('session')->getFlashBag()->add('success', 'Model successfully deleted');

        $lastPage = $this->get('session')->get('seedUnitModels_browse.page');

        return $this->redirect($this->generateUrl('seedUnitModels_browse', array('page' => $lastPage)));
    }

    //    protected function initializePage($title)
    //    {
    //
    //        $this->container->get('elektra.menu')->initialize();
    //
    //        $page = $this->container->get('theme.page');
    //        $page->setBundle('ElektraSiteBundle');
    //        $page->includeEverything();
    //        $page->setHeading($title);
    //        $page->setBodyId('seedunit-models');
    //    }
    //
    //    public function listAction(Request $request, $page)
    //    {
    //
    //        $this->initializePage('Browse Seed Unit Models');
    //
    //        $repository = $this->getDoctrine()->getRepository('ElektraSeedBundle:SeedUnits\Model');
    //        // TODO src: add pagination to the list
    //        $models = $repository->findAll();
    //
    //        // TODO src: also display number of seed units associated with this type
    //
    //        return $this->render("ElektraSeedBundle:SeedUnits/Model:list.html.twig", array('entries' => $models));
    //    }
    //
    //    public function addAction(Request $request)
    //    {
    //
    //        $this->initializePage('Add Seed Unit Models');
    //
    //        $model = new SeedUnitModel();
    //
    //        $formBuilder = $this->createFormBuilder($model);
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
    //            $em->persist($model);
    //            $em->flush();
    //
    //            $this->container->get('session')->getFlashBag()->add('success', ':Success: stored the new model "' . $model->getName() . '"');
    //
    //            return $this->redirect($this->generateUrl('_cesu_seed_unit_models_list'));
    //        }
    //
    //        return $this->render('ElektraSeedBundle:SeedUnits/Model:form.html.twig', array('form' => $form->createView()));
    //    }
    //
    //    public function editAction(Request $request, $id)
    //    {
    //
    //        $this->initializePage('Edit Seed Unit Models');
    //
    //        $repository = $this->getDoctrine()->getRepository('ElektraSeedBundle:SeedUnits\Model');
    //        $model      = $repository->find($id);
    //
    //        $formBuilder = $this->createFormBuilder($model);
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
    //            $this->container->get('session')->getFlashBag()->add('success', ':Success: updated the model "' . $model->getName() . '" - ID ' . $model->getModelId());
    //
    //            return $this->redirect($this->generateUrl('_cesu_seed_unit_models_list'));
    //        }
    //
    //        return $this->render('ElektraSeedBundle:SeedUnits/Model:form.html.twig', array('form' => $form->createView()));
    //    }
    //
    //    public function deleteAction(Request $request, $id)
    //    {
    //
    //        // TODO src: check for references
    //
    //        $repository = $this->getDoctrine()->getRepository('ElektraSeedBundle:SeedUnits\Model');
    //        $model      = $repository->find($id);
    //
    //        if (!$model->getCanDelete()) {
    //            $this->container->get('session')->getFlashBag()->add(
    //                'error',
    //                ':Not possible: the model "' . $model->getName() . '" - ID ' . $model->getId() . ' cannot be deleted (seed units associated)'
    //            );
    //
    //            return $this->redirect($this->generateUrl('_cesu_seed_unit_models_list'));
    //        }
    //
    //        $this->getDoctrine()->getManager()->remove($model);
    //        $this->getDoctrine()->getManager()->flush();
    //
    //        $this->container->get('session')->getFlashBag()->add('success', ':Success: deleted the model "' . $model->getName() . '" - ID ' . $model->getModelId());
    //
    //        return $this->redirect($this->generateUrl('_cesu_seed_unit_models_list'));
    //    }
    //
    //    protected function initializeTheme()
    //    {
    //
    //        $this->container->get('elektra.twig.theme_extension')->initializeComplete();
    //        $theme = $this->container->get('theme');
    //
    //        return $theme;
    //    }
}