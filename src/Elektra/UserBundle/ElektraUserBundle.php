<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class ElektraUserBundle
 *
 * @package Elektra\UserBundle
 *
 * @version 0.1-dev
 */
class ElektraUserBundle extends Bundle
{

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {

        return "FOSUserBundle";
    }
}