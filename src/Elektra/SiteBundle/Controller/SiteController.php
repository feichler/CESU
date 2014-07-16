<?php


namespace Elektra\SiteBundle\Controller;

use Assetic\Factory\Resource\FileResource;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SiteController extends Controller
{

    public function homeAction()
    {
//        $am = $this->container->get('assetic.asset_manager');
//
//$am->addResource(new FileResource("@AurealisThemeBundle/Resources/private/js/jquery-1.11.1.js"), 'fileloader' );
//
//
//        $page = array(
//            'scripts' => array(
//                'common' => true,
//            ),
//        );

        return $this->render('ElektraSiteBundle:Home:index.html.twig');
    }
}