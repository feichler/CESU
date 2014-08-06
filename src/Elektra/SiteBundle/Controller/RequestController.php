<?php

namespace Elektra\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RequestController extends Controller
{

    public function stepsAction(Request $request, $step, $number)
    {

        $this->container->get('elektra.twig.theme_extension')->initializeComplete();

        $theme = $this->container->get('theme');
        $theme->setSubTemplate('navbar', 'ElektraSiteBundle:Parts/Navigation:request-navbar.html.twig');
        $theme->setSubTemplate('footer', 'ElektraSiteBundle:Parts/Footer:request-footer.html.twig');
        // TODO src: set the correct brand name or remove
        $theme->setPageVar('navbar.brand.name', 'TODO: short name');
        // TODO src: set the correct brand route or remove
        //        $theme->setPageVar('navbar.brand.route', 'TODO');
        $theme->setPageVar('heading', 'Cisco ASA with FirePOWER Services');

        return $this->render('ElektraThemeBundle::layout.html.twig');
    }
}