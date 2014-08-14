<?php

namespace Elektra\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;

abstract class Controller extends BaseController
{

    protected function getInitialiseOptions()
    {

        $options = array(
            'override_domain' => 'ElektraSite',
        );

        return $options;
    }
}