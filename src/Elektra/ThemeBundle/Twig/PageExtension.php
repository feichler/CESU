<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\ThemeBundle\Twig;

use Elektra\ThemeBundle\Page\Page;

/**
 * Class PageExtension
 *
 * @package Elektra\ThemeBundle\Twig
 *
 * @version 0.1-dev
 */
class PageExtension extends \Twig_Extension
{

    /**
     * The page object
     *
     * @var Page
     */
    private $page;

    /**
     *
     *
     * @param Page $page
     */
    public function __construct(Page $page)
    {

        $this->page = $page;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {

        return 'page_extension';
    }

    /**
     * {@inheritdoc}
     */
    public function getGlobals()
    {

        return array('page' => $this->page);
    }

    /**
     * Complete initialisation of the page object
     */
    public function initialise()
    {

        // add the defaults for meta information
        $this->page->setViewPort('width=device-width, initial-scale=1.0');

        // add the scripts
        $this->page->addScript('jquery');
        $this->page->addScript('bootstrap');

        // add the styles
        $this->page->addStylesheet('bootstrap');
        $this->page->addStylesheet('fontAwesome');
        $this->page->addStylesheet('theme');

        // add the default areas with templates sub-templates
        $this->page->addArea('messages', 'ElektraThemeBundle:Areas:messages.html.twig');
        $this->page->addArea('navbar', 'ElektraThemeBundle:Areas:navbar.html.twig');
        $this->page->addArea('footer', 'ElektraThemeBundle:Areas:footer.html.twig');

        // Add some generic translations
        $this->page->setLang('toggle_navbar', 'lang.theme.generic.toggle_navbar', true, 'ElektraTheme');
        $this->page->setLang('toggle_footer', 'lang.theme.generic.toggle_footer', true, 'ElektraTheme');
        $this->page->setLang('confirm_delete', 'lang.theme.generic.confirm_delete', true, 'ElektraTheme');
        $this->page->setLang('table_empty', 'lang.theme.generic.table_empty', true, 'ElektraTheme');
        $this->page->setLang('action.browse', 'lang.theme.generic.actions.browse', true, 'ElektraTheme');
        $this->page->setLang('action.view', 'lang.theme.generic.actions.view', true, 'ElektraTheme');
        $this->page->setLang('action.add', 'lang.theme.generic.actions.add', true, 'ElektraTheme');
        $this->page->setLang('action.edit', 'lang.theme.generic.actions.edit', true, 'ElektraTheme');
        $this->page->setLang('action.delete', 'lang.theme.generic.actions.delete', true, 'ElektraTheme');
    }
}