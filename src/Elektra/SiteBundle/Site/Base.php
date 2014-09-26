<?php

namespace Elektra\SiteBundle\Site;

use Elektra\CrudBundle\Crud\Definition;
use Elektra\SiteBundle\Menu\Group;
use Elektra\SiteBundle\Menu\Item;
use Elektra\SiteBundle\Menu\Menu;
use Elektra\SiteBundle\Menu\Separator;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Base
{

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var array
     */
    protected $parameters;

    /**
     * @var array
     */
    protected $areas;

    /**
     * @var array
     */
    protected $variables;

    /**
     * @var array
     */
    protected $styleSheets;

    /**
     * @var array
     */
    protected $scripts;

    /**
     * @var Language
     */
    protected $language;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {

        $this->container = $container;

        // initialise variables with defaults
        $this->parameters  = array(
            'character_set' => 'UTF-8',
            'metadata'      => array(
                'viewport' => 'width=device-width, initial-scale=1.0',
            ),
            'favicon'       => 'favicon.ico',
            'serverDomain'  => 'elektra.aurealis.at'
        );
        $this->areas       = array(
            'navbar'   => 'ElektraSiteBundle:base:navbar.html.twig',
            'footer'   => 'ElektraSiteBundle:base:footer.html.twig',
            'messages' => 'ElektraSiteBundle:base:messages.html.twig',
        );
        $this->variables   = array();
        $this->styleSheets = array(
            'bootstrap'   => true,
            'fontAwesome' => true,
            'theme'       => true,
        );
        $this->scripts     = array(
            'jQuery'    => true,
            'bootstrap' => true,
        );

        $this->setVariable('route.brand', 'index');

        /**
         * Add user specific language strings if logged in
         */
        $security = $this->container->get('security.context');
        if ($security) {
            $token = $security->getToken();
            if ($token) {
                if ($security->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
                    $user = $token->getUser();
                    $this->container->get('siteLanguage')->add(
                        'user.logged_in',
                        'user.logged_in',
                        array(
                            'firstname' => $user->getFirstName(),
                            'lastname'  => $user->getLastName(),
                            'username'  => $user->getUsername(),
                        )
                    );
                }
            }
        }
    }

    /**
     * @param string $name
     * @param mixed  $value
     */
    public function setVariable($name, $value)
    {

        $this->variables[$name] = $value;
    }

    /**
     * @param string $name
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getVariable($name, $default = null)
    {

        if (array_key_exists($name, $this->variables)) {
            return $this->variables[$name];
        }

        return $default;
    }

    /**
     * @param string $name
     * @param string $value
     */
    public function setParameter($name, $value)
    {

        if (array_key_exists($name, $this->parameters)) {
            $this->parameters[$name] = $value;
        } else {
            $this->setVariable($name, $value);
        }
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function getParameter($name)
    {

        if (array_key_exists($name, $this->parameters)) {
            return $this->parameters[$name];
        }

        return $this->getVariable($name);
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
     */
    public function addStyleSheet($name)
    {

        $this->styleSheets[$name] = true;
    }

    /**
     * @param string $name
     */
    public function removeStyleSheet($name)
    {

        $this->styleSheets[$name] = false;
    }

    /**
     * @return array
     */
    public function getStyleSheets()
    {

        return $this->styleSheets;
    }

    /**
     * @param string $name
     */
    public function addScript($name)
    {

        $this->scripts[$name] = true;
    }

    /**
     * @param string $name
     */
    public function removeScript($name)
    {

        $this->scripts[$name] = false;
    }

    /**
     * @return array
     */
    public function getScripts()
    {

        return $this->scripts;
    }

    /**
     * @param string $name
     * @param string $template
     */
    public function addArea($name, $template)
    {

        $this->areas[$name] = $template;
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public function getArea($name)
    {

        if (array_key_exists($name, $this->areas)) {
            return $this->areas[$name];
        }

        return null;
    }

    /**
     * @return Menu
     */
    public function getMenu()
    {

        return $this->container->get('siteMenu');
    }

    /**
     * @return EngineInterface
     */
    public function getTemplateEngine()
    {

        return $this->container->get('templating');
    }

    public function initialisePage($controller, $action, $langPrefix, $options = array())
    {

        /**
         * Store the given variables (if they are needed anywhere)
         */
        $this->setVariable('controller', $controller);
        $this->setVariable('action', $action);
        $this->setVariable('langPrefix', $langPrefix);

        $this->languageOverrides($langPrefix, $action);
        $this->initialiseMenu($controller, $action);
    }

    public function initialisePageFromDefinition(Definition $definition, $action, $options = array())
    {

        $langPrefix = $definition->getLanguageKey();
        //        $langPrefix = $definition->getGroupLang() . '.' . $definition->getNameLang();
        $controller = $definition->getController();
        $this->initialisePage($controller, $action, $langPrefix, $options);
    }

    protected function languageOverrides($langPrefix, $action = '')
    {

        $prefixParts = explode('.', $langPrefix);
        if ($action != '') {
            $prefixParts[] = $action;
        }

        $key = 'pages';
        foreach ($prefixParts as $prefixPart) {
            $key .= '.' . $prefixPart;
            $this->languageOverride($key);
        }
    }

    private function languageOverride($key)
    {

        $strings  = array('title', 'title_prefix', 'title_suffix', 'heading', 'section');
        $language = $this->container->get('siteLanguage');

        foreach ($strings as $string) {
            $language->override($string, $key . '.' . $string);

            if (!$language->isTranslated($string)) {
                $language->restore($string);
            }
        }
    }

    protected function initialiseMenu($controller, $action)
    {

        $hasMenu = true;

        switch ($controller) {
            case 'ElektraUser:Security':
                $hasMenu = false;
                break;
        }

        if ($hasMenu) {
            $siteMenu = $this->container->get('siteMenu');

            // URGENT initialise the main menu for the site

            // first item - reports
            $siteMenu->addItem($this->getReportsMenu());
            // second item - requests
            $siteMenu->addItem($this->getRequestsMenu());
            // third item - companies (partner / customer / sales team)
            $siteMenu->addItem($this->getCompaniesMenu());
            // fourth item - master data
            $siteMenu->addItem($this->getMasterDataMenu());
            // fifth item - imports
            $siteMenu->addItem($this->getImportMenu());
        }
    }

    private function getRequestsMenu()
    {

        $siteLanguage = $this->container->get('siteLanguage');
        $navigator    = $this->container->get('navigator');

        $requestsItem = new Item($siteLanguage->getRequired('menu.requests'), $navigator->getBrowseLink($navigator->getDefinition('Elektra', 'Seed', 'Requests', 'Request')));

        return $requestsItem;
    }

    private function getReportsMenu()
    {

        $siteLanguage = $this->container->get('siteLanguage');

        // TODO reports definition
        $reportsItem = new Item($siteLanguage->getRequired('menu.reports'));

        return $reportsItem;
    }

    private function getCompaniesMenu()
    {

        $siteLanguage = $this->container->get('siteLanguage');
        $navigator    = $this->container->get('navigator');

        $companiesItem = new Group($siteLanguage->getRequired('menu.companies'));

        $companiesItem->addItem(
            new Item($siteLanguage->getRequired('menu.partners'), $navigator->getBrowseLink($navigator->getDefinition('Elektra', 'Seed', 'Companies', 'Partner')))
        );
        $companiesItem->addItem(
            new Item($siteLanguage->getRequired('menu.customers'), $navigator->getBrowseLink($navigator->getDefinition('Elektra', 'Seed', 'Companies', 'Customer')))
        );

        return $companiesItem;
    }

    private function getMasterDataMenu()
    {

        $siteLanguage = $this->container->get('siteLanguage');
        $navigator    = $this->container->get('navigator');

        $masterDataItem = new Group($siteLanguage->getRequired('menu.master_data'));

        // Seed Units Sub-Menu
//        $seedUnits = new Group($siteLanguage->getRequired('menu.seed_units'));
        $masterDataItem->addItem(
            new Item($siteLanguage->getRequired('menu.seed_units'), $navigator->getBrowseLink($navigator->getDefinition('Elektra', 'Seed', 'SeedUnits', 'SeedUnit')))
        );
        $masterDataItem->addItem(
            new Item($siteLanguage->getRequired('menu.seed_unit_models'), $navigator->getBrowseLink($navigator->getDefinition('Elektra', 'Seed', 'SeedUnits', 'Model')))
        );
        $masterDataItem->addItem(
            new Item($siteLanguage->getRequired('menu.seed_unit_power_cord_types'), $navigator->getBrowseLink($navigator->getDefinition('Elektra', 'Seed', 'SeedUnits', 'PowerCordType')))
        );
//        $masterDataItem->addItem($seedUnits);
        $masterDataItem->addItem(new Separator());

        $masterDataItem->addItem(
            new Item($siteLanguage->getRequired('menu.partner_types'), $navigator->getBrowseLink($navigator->getDefinition('Elektra', 'Seed', 'Companies', 'PartnerType')))
        );
//        $masterDataItem->addItem($seedUnits);
        $masterDataItem->addItem(new Separator());

        // Trainings Sub-Menu
//        $trainings = new Group($siteLanguage->getRequired('menu.trainings'));
//        // URGENT add links for the training sub-menu
//        $trainings->addItem(
//            new Item($siteLanguage->getRequired('menu.trainings'), $navigator->getBrowseLink($navigator->getDefinition('Elektra', 'Seed', 'Trainings', 'Training')))
//        );
//        $trainings->addItem(
//            new Item($siteLanguage->getRequired('menu.registrations'), $navigator->getBrowseLink($navigator->getDefinition('Elektra', 'Seed', 'Trainings', 'Registration')))
//        );
//        $trainings->addItem(
//            new Item($siteLanguage->getRequired('menu.attendances'), $navigator->getBrowseLink($navigator->getDefinition('Elektra', 'Seed', 'Trainings', 'Attendance')))
//        );
//        $masterDataItem->addItem($trainings);
//        $masterDataItem->addItem(new Separator());

        // Geographic Sub-Menu
//        $geographic = new Group($siteLanguage->getRequired('menu.geographic'));
        $masterDataItem->addItem(
            new Item($siteLanguage->getRequired('menu.regions'), $navigator->getBrowseLink($navigator->getDefinition('Elektra', 'Seed', 'Companies', 'Region')))
        );
        $masterDataItem->addItem(
            new Item($siteLanguage->getRequired('menu.countries'), $navigator->getBrowseLink($navigator->getDefinition('Elektra', 'Seed', 'Companies', 'Country')))
        );
//        $masterDataItem->addItem($geographic);
        $masterDataItem->addItem(new Separator());

//        $misc = new Group($siteLanguage->getRequired('menu.misc'));
        $masterDataItem->addItem(
          new Item($siteLanguage->getRequired('menu.warehouses'), $navigator->getBrowseLink($navigator->getDefinition('Elektra','Seed','Companies','WarehouseLocation')))
        );
//        $masterDataItem->addItem($misc);

        return $masterDataItem;
    }

    public function getImportMenu()
    {

        $siteLanguage = $this->container->get('siteLanguage');
        $navigator    = $this->container->get('navigator');

        $importItem = new Group($siteLanguage->getRequired('menu.import'));
        $importItem->addItem(
            new Item($siteLanguage->getRequired('menu.import_seed_units'), $navigator->getBrowseLink($navigator->getDefinition('Elektra', 'Seed', 'Imports', 'SeedUnit')))
        );

        return $importItem;
    }
}