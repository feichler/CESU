<?php

namespace Elektra\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SiteController extends Controller
{

    public function homeAction()
    {

        return $this->render('ElektraSiteBundle:Home:index.html.twig', array('page' => array('a'=>'b','bodyId'=>'home','bodyClasses'=>array('some-class','some-other-class'))));
    }
}