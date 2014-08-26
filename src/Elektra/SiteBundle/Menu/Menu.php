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
 * Class Menu
 *
 * @package Elektra\SiteBundle\Menu
 *
 * @version 0.1-dev
 */
class Menu extends Group
{

    /**
     *
     */
    public function __construct()
    {

        parent::__construct('');
        $this->items = array();
    }

    /**
     *
     */
    public function prepare()
    {

        parent::setLevels(0);
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplate()
    {

        return 'ElektraSiteBundle:menu:menu.html.twig';
    }
}