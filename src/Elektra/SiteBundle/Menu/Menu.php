<?php

namespace Elektra\SiteBundle\Menu;

use Aurealis\ThemeBundle\Menu\Menu as BaseMenu;

class Menu
{

    /**
     * @var BaseMenu
     */
    protected $menu;

    public function __construct(BaseMenu $menu)
    {

        $this->menu = $menu;
    }

    public function initialize()
    {
    }
}