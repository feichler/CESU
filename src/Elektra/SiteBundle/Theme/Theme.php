<?php

namespace Elektra\SiteBundle\Theme;

use Symfony\Component\DependencyInjection\ContainerInterface;

class Theme
{

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Constructor
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {

        $this->container = $container;
    }

    public function initialiseCRUDPage($type, $langKey, $sectionKey, $action)
    {

        $theme_extension = $this->container->get('elektra_theme.twig.theme_extension');
        $theme_extension->initializeComplete();

        $page_theme = $this->container->get('theme');

        $this->setSubTemplates($page_theme, $type);
        $this->setPageBrand($page_theme, $type);

        $this->setPageTitle($page_theme, $type, $langKey);
        if ($action == 'browse') {
            $this->setPageHeading($page_theme, $type, $langKey, $action, true);
        } else {
            $this->setPageHeading($page_theme, $type, $langKey, $action, false);
        }
        $this->setPageSection($page_theme, $type, $sectionKey);
    }

    public function initialiseAdminPage()
    {

        $theme_extension = $this->container->get('elektra_theme.twig.theme_extension');
        $theme_extension->initializeComplete();

        $page_theme = $this->container->get('theme');

        $this->setSubTemplates($page_theme, 'admin');
        $this->setPageBrand($page_theme, 'admin');

        $page_theme->setPageVar('title', 'site.admin.title_suffix');
        $page_theme->setPageVar('heading', 'site.admin.heading');
        $page_theme->setPageVar('heading_p', 1);

        $this->setPageSection($page_theme, 'admin', 'admin');
    }

    public function initialiseRequestPage()
    {
    }

    protected function setSubTemplates(\Elektra\ThemeBundle\Theme\Theme $page_theme, $type)
    {

        switch ($type) {
            case 'admin':
                $page_theme->setSubTemplate('navbar', 'ElektraSiteBundle:Parts/Navigation:admin-navbar.html.twig');
                $page_theme->setSubTemplate('footer', 'ElektraSiteBundle:Parts/Footer:admin-footer.html.twig');
                break;
            case 'request':
                $page_theme->setSubTemplate('navbar', 'ElektraSiteBundle:Parts/Navigation:request-navbar.html.twig');
                $page_theme->setSubTemplate('footer', 'ElektraSiteBundle:Parts/Footer:request-footer.html.twig');
                break;
            case 'user':
                $page_theme->setSubTemplate('footer', 'ElektraSiteBundle:Parts/Footer:admin-footer.html.twig');
                break;
        }
    }

    protected function setPageTitle(\Elektra\ThemeBundle\Theme\Theme $page_theme, $type, $key)
    {

        //        echo '<b>page title:</b> <br />';
        //        echo 'key: ' . $key . '<br />';

        $page_theme->setPageVar('title', 'site.' . $type . '.entities.' . $key . '.title');
        $page_theme->setPageVar('title_prefix', 'site.' . $type . '.title_prefix');
        $page_theme->setPageVar('title_suffix', 'site.' . $type . '.title_suffix');
        //
        //        echo 'pageVar title: ' . $page_theme->getPageVar('title') . '<br />';
        //        echo 'pageVar title_prefix: ' . $page_theme->getPageVar('title_prefix') . '<br />';
        //        echo 'pageVar title_suffix: ' . $page_theme->getPageVar('title_suffix') . '<br />';
    }

    protected function setPageBrand(\Elektra\ThemeBundle\Theme\Theme $page_theme, $type)
    {

        $page_theme->setPageVar('brand.name', 'site.' . $type . '.brand_name');

        switch ($type) {
            case 'admin':
                $page_theme->setPageVar('brand.route', 'ElektraSiteBundle_admin_index');
                break;
            // TODO src: check if other routes are needed for the brand item
        }
    }

    protected function setPageHeading(\Elektra\ThemeBundle\Theme\Theme $page_theme, $type, $key, $action, $plural = false)
    {

        //        echo '<b>page heading:</b> <br />';
        //        echo 'key: ' . $key . '<br />';

        if ($key != '') {
            $page_theme->setPageVar('heading', 'site.' . $type . '.entities.' . $key . '.heading');
            $page_theme->setPageVar('heading_a', 'site.' . $type . '.actions.' . $action);
        }
        if ($plural) {
            $page_theme->setPageVar('heading_p', 2);
        } else {
            $page_theme->setPageVar('heading_p', 1);
        }

        //        echo 'pageVar heading: ' . $page_theme->getPageVar('heading') . '<br />';
        //        echo 'pageVar heading_a: ' . $page_theme->getPageVar('heading_a') . '<br />';
        //        echo 'pageVar heading_p: ' . $page_theme->getPageVar('heading_p') . '<br />';
    }

    protected function setPageSection(\Elektra\ThemeBundle\Theme\Theme $page_theme, $type, $key)
    {

        if ($key != '') {
            $page_theme->setPageVar('section', 'site.' . $type . '.sections.' . $key);
        }
    }
}