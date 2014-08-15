<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;

/**
 * Class Controller
 *
 * @package Elektra\SeedBundle\Controller
 *
 * @version 0.1-dev
 */
abstract class Controller extends BaseController
{

    /**
     * @return array
     */
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