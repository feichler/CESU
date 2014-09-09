<?php

namespace Elektra\SiteBundle\Twig;

use Elektra\SiteBundle\Menu\Item;
use Elektra\SiteBundle\Menu\Menu;
use Elektra\SiteBundle\Site\Base;
use Elektra\SiteBundle\Site\Language;

class SiteExtension extends \Twig_Extension
{

    /**
     * @var Base
     */
    protected $siteBase;

    /**
     * @var Language
     */
    protected $siteLanguage;

    /**
     * @param Base     $siteBase
     * @param Language $siteLanguage
     */
    public function __construct(Base $siteBase, Language $siteLanguage)
    {

        $this->siteBase     = $siteBase;
        $this->siteLanguage = $siteLanguage;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {

        return 'site_extension';
    }

    /**
     * {@inheritdoc}
     */
    public function getGlobals()
    {

        return array(
            'siteBase'     => $this->siteBase,
            'siteLanguage' => $this->siteLanguage,
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {

        return array(
            'siteMenu'     => new \Twig_SimpleFunction('siteMenu', array($this, 'renderMenu'), array(
                    'is_safe' => array('html'),
                )),
            'siteMenuItem' => new \Twig_SimpleFunction('siteMenuItem', array($this, 'renderMenuItem'), array(
                    'is_safe' => array('html'),
                )),
            'siteLanguage' => new \Twig_SimpleFunction('siteLanguage', array($this, 'language')),
        );
    }

    /**
     * @param string $key
     *
     * @return string
     */
    public function language($key)
    {

        return $this->siteLanguage->get($key);
    }

    /**
     * @param Menu $menu
     *
     * @return string
     */
    public function renderMenu(Menu $menu)
    {

        $menu->prepare();

        return $this->siteBase->getTemplateEngine()->render($menu->getTemplate(), array('item' => $menu));
    }

    /**
     * @param Item $item
     *
     * @return string
     */
    public function renderMenuItem(Item $item)
    {

        return $this->siteBase->getTemplateEngine()->render($item->getTemplate(), array('item' => $item));
    }
}