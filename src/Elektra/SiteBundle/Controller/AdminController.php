<?php

namespace Elektra\SiteBundle\Controller;

use Elektra\ThemeBundle\Table\Table;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{

    private function initialise($action)
    {

        $options = $this->getInitialiseOptions();

        // TODO add language overrides

        $page = $this->container->get('page');
        $page->initialiseAdminPage('admin', $action, $options);
    }

    public function indexAction(Request $request)
    {

        $this->initialise('index');

        // TODO src define and implement content to be displayed

        return $this->render('ElektraSiteBundle:Admin:index.html.twig');
    }
}