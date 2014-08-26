<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SiteBundle\Menu;

/**
 * Class Separator
 *
 * @package Elektra\SiteBundle\Menu
 *
 * @version 0.1-dev
 */
class Separator extends Item
{

    /**
     *
     */
    public function __construct()
    {

        parent::__construct('');
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplate()
    {

        return 'ElektraSiteBundle:menu:separator.html.twig';
    }
}