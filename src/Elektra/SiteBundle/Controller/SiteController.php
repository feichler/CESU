<?php

namespace Elektra\SiteBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class SiteController extends Controller
{

    public function homeAction(Request $request)
    {

        $this->container->get('elektra.menu')->initialize();
        //        $session = $this->container->get('session');
        //        $session->getFlashBag()->add('error',':test: test');
        //        $session->getFlashBag()->add('warning', ':test: test');
        //        $session->getFlashBag()->add('info',':test: test');
        //        $session->getFlashBag()->add('success',':test: test');
        $page = $this->container->get('theme.page');
        $page->setBundle('ElektraSiteBundle');
        $page->includeEverything();
        //        $page->includeBootstrapComplete();
        //        $page->includeFontAwesomeStyles();
        //        $page->includeThemeStyles();
        $page->setHeading('Home');

        $this->container->get('theme.page')->setBodyId('asdf');
        $pagination = $this->container->get('theme.pagination');

        return $this->render('ElektraSiteBundle::layout.html.twig');
        //        return new Response('asdf');
    }

    public function aboutAction(Request $request)
    {

        $this->container->get('elektra.menu')->initialize();

        $page = $this->container->get('theme.page');
        $page->setBundle('ElektraSiteBundle');
        $page->includeEverything();
        $page->setHeading('About Us');

        return $this->render('ElektraSiteBundle:Static:about.html.twig');
    }

    public function termsAction(Request $request)
    {

        $this->container->get('elektra.menu')->initialize();

        $page = $this->container->get('theme.page');
        $page->setBundle('ElektraSiteBundle');
        $page->includeEverything();
        $page->setHeading('Terms & Conditions');

        return $this->render('ElektraSiteBundle:Static:terms.html.twig');
    }
}