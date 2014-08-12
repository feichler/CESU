<?php

namespace Elektra\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{

    private function initialise() {

        $theme = $this->get('site');
$theme->initialiseAdminPage();
    }

    public function indexAction(Request $request)
    {
        $this->initialise();

//        $site= $this->container->get('site');
//        $site->initializeAdminPage('Home', 'Administration');

//        $theme = $this->initializeTheme();
//        $theme->setPageVar('navbar.brand.name', 'CESU Admin');
//        $theme->setPageVar('navbar.brand.route', 'admin_home');
//        $theme->setPageVar('title', 'Home - CESU Administration');
//        $theme->setPageVar('heading', 'Cisco Elektra Seed Unit Administration');
//
//        $theme->setSubTemplate('navbar', 'ElektraSiteBundle:Parts/Navigation:admin-navbar.html.twig');
//        $theme->setSubTemplate('footer', 'ElektraSiteBundle:Parts/Footer:admin-footer.html.twig');

        return $this->render('ElektraSiteBundle:Admin:index.html.twig');
    }

    protected function initializeTheme()
    {

        $this->container->get('elektra.twig.theme_extension')->initializeComplete();
        $theme = $this->container->get('theme');

        return $theme;
    }
}