<?php

namespace Elektra\SiteBundle\Theme;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Theme // implements ContainerAwareInterface
{

    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {

        $this->container = $container;
    }

    /**
     * @inheritdoc
     */
    public function setContainer(ContainerInterface $container = null)
    {

        $this->container = $container;
    }

    public function initializeRequestPage($title, $heading, $headingSection = '')
    {

        // BIG TODO src - add translations
        $theme = $this->container->get('theme');

        /*
         * Initialize the template itself
         */
        $this->initializeTemplate();

        /*
         * Set the sub-templates
         */
        $theme->setSubTemplate('navbar', 'ElektraSiteBundle:Parts/Navigation:request-navbar.html.twig');
        $theme->setSubTemplate('footer', 'ElektraSiteBundle:Parts/Footer:request-footer.html.twig');

        /*
         * Set the default routes
         */
        $theme->setPageVar('navbar.brand.route', 'ElektraSiteBundle_request_index');

        /*
         * Set the strings
         */
        // html title
        $theme->setPageVar('title', $title . ' - ' . 'translation missing');
//        $theme->setPageVar('title', $title . ' - ' . $this->container->getParameter('site_lang.request.page_title_suffix'));

        // brand item name
        $theme->setPageVar('navbar.brand.name', 'translation missing');
//        $theme->setPageVar('navbar.brand.name', $this->container->getParameter('site_lang.request.page_name'));

        // page heading
        $this->setPageHeading('request', $heading, $headingSection);
    }

    public function initializeAdminPage($title, $heading, $headingSection = '')
    {

        $theme = $this->container->get('theme');

        /*
         * Initialize the template itself
         */
        $this->initializeTemplate();

        /*
         * Set the sub-templates
         */
        $theme->setSubTemplate('navbar', 'ElektraSiteBundle:Parts/Navigation:admin-navbar.html.twig');
        $theme->setSubTemplate('footer', 'ElektraSiteBundle:Parts/Footer:admin-footer.html.twig');

        /*
         * Set the default routes
         */
        $theme->setPageVar('navbar.brand.route', 'ElektraSiteBundle_admin_index');

        /*
         * Set the strings
         */
        // html title
        $theme->setPageVar('title', $title . ' - ' . 'translation missing');
//        $theme->setPageVar('title', $title . ' - ' . $this->container->getParameter('site_lang.admin.page_title_suffix'));

        // brand item name
        $theme->setPageVar('navbar.brand.name', 'translation missing');
//        $theme->setPageVar('navbar.brand.name', $this->container->getParameter('site_lang.admin.page_name'));

        // page heading
        $this->setPageHeading('admin', $heading, $headingSection);
    }

    public function initializeUserPage($title, $heading, $headingSection = '')
    {

        $theme = $this->container->get('theme');

        /*
         * Initialize the template itself
         */
        $this->initializeTemplate();

        /*
         * Set the sub-templates
         */
        //        $theme->setSubTemplate('navbar', 'ElektraSiteBundle:Parts/Navigation:admin-navbar.html.twig');
        $theme->setSubTemplate('footer', 'ElektraSiteBundle:Parts/Footer:admin-footer.html.twig');

        /*
         * Set the default routes
         */
        $theme->setPageVar('navbar.brand.route', 'ElektraSiteBundle_admin_index');

        /*
         * Set the strings
         */
        // html title
        $theme->setPageVar('title', $title . ' - ' . 'translation missing');
//        $theme->setPageVar('title', $title . ' - ' . $this->container->getParameter('site_lang.admin.page_title_suffix'));

        // brand item name
        $theme->setPageVar('navbar.brand.name', 'translation missing');
//        $theme->setPageVar('navbar.brand.name', $this->container->getParameter('site_lang.admin.page_name'));

        // page heading
        $this->setPageHeading('admin', $heading, $headingSection);
    }

    protected function setPageHeading($type, $heading, $section)
    {

        $theme = $this->container->get('theme');

        if ($section == '') {
            $theme->setPageVar('heading', $heading);
        } else {
            $key = 'site_lang.' . $type . '.' . $section;
            $theme->setPageVar('heading', $this->container->getParameter('translation missing'));
//            $theme->setPageVar('heading', $this->container->getParameter($key));
            $theme->setPageVar('subheading', $heading);
        }
    }

    protected function initializeTemplate()
    {

        $this->container->get('elektra_theme.twig.theme_extension')->initializeComplete();
    }
}