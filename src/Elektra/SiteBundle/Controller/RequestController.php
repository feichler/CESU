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

//        $theme = $this->container->get('theme');
//        $theme->useEverything();

        //        var_dump($theme);
        return $this->render('ElektraThemeBundle::layout.html.twig');

        return new Response('Request steps: ' . $step . ' - ' . $number);
    }
}