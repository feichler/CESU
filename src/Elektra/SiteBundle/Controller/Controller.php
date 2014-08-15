<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;

/**
 * Class Controller
 *
 * @package Elektra\SiteBundle\Controller
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

        $options = array(
            'override_domain' => 'ElektraSite',
        );

        return $options;
    }
}