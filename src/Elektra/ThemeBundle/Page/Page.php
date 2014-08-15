<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\ThemeBundle\Page;

use Elektra\ThemeBundle\Page\Overrides\Language;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class Page
 *
 * @package Elektra\ThemeBundle\Page
 *
 * @version 0.1-dev
 */
class Page
{

    /**
     * The service container
     *
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Array containing various page parameters to configure the HTML output of the template
     *
     * @var array
     */
    protected $pageParameters;

    /**
     * Array of translated strings for the template
     *
     * @var array
     */
    protected $language;

    /**
     *
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {

        $this->container      = $container;
        $this->pageParameters = array(
            // setting defaults
            'character_set' => 'UTF-8',
            'metadata'      => array(),
            'favicon'       => 'favicon.ico',
            'scripts'       => array(
                'jquery'    => false,
                'bootstrap' => false,
            ),
            'styles'        => array(
                'bootstrap'   => false,
                'fontAwesome' => false,
                'theme'       => false,
            ),
            'areas'         => array(),
            'templates'     => array(),
            'variables'     => array(),
            'activeRoute'   => '',
        );
        $this->language       = array();
    }

    /**
     * @param string $route
     */
    public function setActiveRoute($route)
    {

        $this->pageParameters['activeRoute'] = $route;
    }

    /**
     * @return string
     */
    public function getActiveRoute()
    {

        return $this->pageParameters['activeRoute'];
    }

    /**
     * @param string $name
     * @param string $string
     * @param bool   $translate
     * @param null   $domain
     */
    public function setLang($name, $string, $translate = false, $domain = null)
    {

        if ($translate) {
            $translator = $this->container->get('translator.default');
            $translated = $translator->trans($string, array(), $domain);

            $this->language[$name] = $translated;
        } else {
            $this->language[$name] = $string;
        }
    }

    /**
     * @param string $name
     * @param string $string
     */
    public function setString($name, $string)
    {

        $this->setLang($name, $string);
    }

    /**
     * @param string $name
     *
     * @return string|null
     */
    public function getLang($name)
    {

        if (array_key_exists($name, $this->language)) {
            return $this->language[$name];
        }

        return null;
    }

    /**
     * @param string $name
     *
     * @return string|null
     */
    public function getString($name)
    {

        return $this->getLang($name);
    }

    /**
     * @param string $characterSet
     */
    public function setCharacterSet($characterSet)
    {

        $this->pageParameters['character_set'] = $characterSet;
    }

    /**
     * @return string
     */
    public function getCharacterSet()
    {

        return $this->pageParameters['character_set'];
    }

    /**
     * @param string $name
     * @param string $content
     */
    public function addMetadata($name, $content)
    {

        $this->pageParameters['metadata'][$name] = $content;
    }

    /**
     * @return array
     */
    public function getMetadata()
    {

        return $this->pageParameters['metadata'];
    }

    /**
     * @param string $viewPort
     */
    public function setViewPort($viewPort)
    {

        $this->addMetadata('viewport', $viewPort);
    }

    /**
     * @param string $favIcon
     */
    public function setFavIcon($favIcon)
    {

        $this->pageParameters['favicon'] = $favIcon;
    }

    /**
     * @return string
     */
    public function getFavIcon()
    {

        return $this->pageParameters['favicon'];
    }

    /**
     * @param string $name
     */
    public function addStylesheet($name)
    {

        $this->pageParameters['styles'][$name] = true;
    }

    /**
     * @return array
     */
    public function getStylesheets()
    {

        return $this->pageParameters['styles'];
    }

    /**
     * @param string $name
     */
    public function addScript($name)
    {

        $this->pageParameters['scripts'][$name] = true;
    }

    /**
     * @return array
     */
    public function getScripts()
    {

        return $this->pageParameters['scripts'];
    }

    /**
     * @param string $name
     * @param string $template
     */
    public function addArea($name, $template)
    {

        $this->pageParameters['areas'][$name] = $template;
    }

    /**
     * @param string $name
     *
     * @return string|null
     */
    public function getArea($name)
    {

        if (array_key_exists($name, $this->pageParameters['areas'])) {
            return $this->pageParameters['areas'][$name];
        }

        return null;
    }

    /**
     * @return bool
     */
    public function hasMessages()
    {

        $bag = $this->container->get('session')->getFlashBag();

        $types = array(
            'error',
            'warning',
            'info',
            'success',
        );

        foreach ($types as $type) {
            if ($bag->has($type)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $name
     * @param mixed  $value
     */
    public function setVariable($name, $value)
    {

        $this->pageParameters['variables'][$name] = $value;
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function getVariable($name)
    {

        if (array_key_exists($name, $this->pageParameters['variables'])) {
            return $this->pageParameters['variables'][$name];
        }

        return null;
    }

    /**
     * @param string $langSection
     * @param string $controller
     * @param string $action
     * @param array  $options
     */
    protected function initialise($langSection, $controller, $action, $options)
    {

        // This function sets all defaults for the site - the calling functions need to override any specifics

        $this->initialiseBase();

        $areaPrefix = 'ElektraSiteBundle:Areas/' . ucfirst($controller) . ':';
        $this->addArea('navbar', $areaPrefix . 'navbar.html.twig');
        $this->addArea('footer', $areaPrefix . 'footer.html.twig');

        // store the parameters in the variables, if the rendering needs it
        $this->setVariable('langSection', $langSection);
        $this->setVariable('controller', $controller);
        $this->setVariable('action', $action);
        $this->setVariable('options', $options);

        // set the copyright language string
        $copyrightKey = 'lang.' . $langSection . '.copyright';
        $this->setLang('copyright', $copyrightKey, true, 'ElektraSite');

        // set the page title items
        $titleKey       = 'lang.' . $langSection . '.title.brand';
        $titlePrefixKey = 'lang.' . $langSection . '.title.prefix';
        $titleSuffixKey = 'lang.' . $langSection . '.title.suffix';
        $this->setLang('title', $titleKey, true, 'ElektraSite');
        $this->setLang('title_prefix', $titlePrefixKey, true, 'ElektraSite');
        $this->setLang('title_suffix', $titleSuffixKey, true, 'ElektraSite');

        // set the brand item
        $brandNameKey = 'lang.' . $langSection . '.title.brand';
        $this->setLang('brand_name', $brandNameKey, true, 'ElektraSite');

        // set the heading & section
        $genericHeadingKey = 'lang.' . $langSection . '.pages.generic.heading';
        $genericSectionKey = 'lang.' . $langSection . '.pages.generic.section';
        $this->setLang('heading', $genericHeadingKey, true, 'ElektraSite');
        $this->setLang('section', $genericSectionKey, true, 'ElektraSite');

        if (isset($options['language'])) {
            $this->initialiseLanguageOverrides($options['language']);
        }
    }

    /**
     *
     */
    private function initialiseBase()
    {

        // add the defaults for meta information
        $this->setViewPort('width=device-width, initial-scale=1.0');

        // add the scripts
        $this->addScript('jquery');
        $this->addScript('bootstrap');

        // add the styles
        $this->addStylesheet('bootstrap');
        $this->addStylesheet('fontAwesome');
        $this->addStylesheet('theme');

        // add the default areas with templates sub-templates
        $this->addArea('messages', 'ElektraThemeBundle:Areas:messages.html.twig');
        $this->addArea('navbar', 'ElektraThemeBundle:Areas:navbar.html.twig');
        $this->addArea('footer', 'ElektraThemeBundle:Areas:footer.html.twig');

        // Add some generic translations
        $this->setLang('toggle_navbar', 'lang.theme.generic.toggle_navbar', true, 'ElektraTheme');
        $this->setLang('toggle_footer', 'lang.theme.generic.toggle_footer', true, 'ElektraTheme');
        $this->setLang('confirm_delete', 'lang.theme.generic.confirm_delete', true, 'ElektraTheme');
        $this->setLang('table_empty', 'lang.theme.generic.table_empty', true, 'ElektraTheme');
        $this->setLang('action.browse', 'lang.theme.generic.actions.browse', true, 'ElektraTheme');
        $this->setLang('action.view', 'lang.theme.generic.actions.view', true, 'ElektraTheme');
        $this->setLang('action.add', 'lang.theme.generic.actions.add', true, 'ElektraTheme');
        $this->setLang('action.edit', 'lang.theme.generic.actions.edit', true, 'ElektraTheme');
        $this->setLang('action.delete', 'lang.theme.generic.actions.delete', true, 'ElektraTheme');
    }

    /**
     * @param string $controller
     * @param string $action
     * @param array  $options
     */
    public function initialiseAdminPage($controller, $action, $options = array())
    {

        $this->initialise('admin', $controller, $action, $options);

        if ($controller == 'security' && $action == 'login') {
            // the security -> login page uses another navbar & footer
            $this->addArea('navbar', 'ElektraSiteBundle:Areas/User:navbar.html.twig');
            $this->addArea('footer', 'ElektraSiteBundle:Areas/User:footer.html.twig');
        }
    }

    /**
     * @param string $controller
     * @param string $action
     * @param array  $options
     */
    public function initialiseSitePage($controller, $action, $options = array())
    {

        $this->initialise('site', $controller, $action, $options);

        if ($controller == 'request') {
            // the request page uses the default site navbar & footer
            $this->addArea('navbar', 'ElektraSiteBundle:Areas/Site:navbar.html.twig');
            $this->addArea('footer', 'ElektraSiteBundle:Areas/Site:footer.html.twig');
        }
    }

    /**
     * @param array $overrides
     */
    protected function initialiseLanguageOverrides($overrides)
    {

        $translator = $this->container->get('translator.default');
        $logger     = $this->container->get('logger');

        foreach ($overrides as $type => $override) {
            if ($override instanceof Language) {
                $translated = $override->getTranslation($translator);
                if ($translated == '') { // return if not translated -> check for fallback
                    if (!$override->fallback()) {
                        $this->setLang($type, '~~ ' . $type . ' - not translated ~~');
                        $logger->err('No translation found and fallback deactivated at "' . $type . '"');
                    } else {
                        // do nothing -> leave the default
                        $logger->warn('No translation found but fallback activated at "' . $type . '"');
                    }
                } else {
                    $this->setLang($type, $translated);
                }
            } else {
                echo 'INVALID OVERRIDE!<br />';
            }
        }
    }

    /**
     * CHECK method used / required?
     *
     * @param       $key
     * @param       $override
     * @param       $domain
     * @param array $params
     */
    protected function addLanguageOverride($key, $override, $domain, $params = array())
    {
    }

    /**
     * @return bool
     */
    public function getShowDebug()
    {

        $env = $this->container->get('kernel')->getEnvironment();

        if ($env == 'dev') {
            return true;
        }

        return false;
    }
}