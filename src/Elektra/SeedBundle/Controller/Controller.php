<?php

namespace Elektra\SeedBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;

abstract class Controller extends BaseController
{

    protected function getInitialiseOptions()
    {

        $options = array();

        return $options;
    }

    /*************************************************************************
     * Message Helper functions
     *************************************************************************/

    /**
     * @param $type
     * @param $message
     */
    protected function addMessage($type, $message)
    {

        $this->get('session')->getFlashBag()->add($type, $message);
    }
}