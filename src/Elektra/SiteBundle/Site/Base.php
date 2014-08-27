<?php

namespace Elektra\SiteBundle\Site;

use Elektra\CrudBundle\Definition\Definition;
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

        $this->setVariable('route.brand', 'ElektraSiteBundle_index');

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
                        'common.user.generic.logged_in',
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

        $langPrefix = $definition->getGroupLang() . '.' . $definition->getNameLang();
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

            $siteMenu->addItem($this->getRequestsMenu());
            $siteMenu->addItem($this->getReportsMenu());
            $siteMenu->addItem($this->getCompaniesMenu());
            $siteMenu->addItem($this->getMasterDataMenu());

            // CHECK temporary menu items - to be removed / changed
            $navigator      = $this->container->get('navigator');
            $temporaryGroup = new Group('Temporary');

            $temporaryGroup->addItem(new Item('Locations', $navigator->getLink(array('Elektra', 'Seed', 'Companies', 'CompanyLocation'), 'browse')));
            $temporaryGroup->addItem(new Item('Persons', $navigator->getLink(array('Elektra', 'Seed', 'Companies', 'CompanyPerson'), 'browse')));
            $temporaryGroup->addItem(new Item('Contact Infos', $navigator->getLink(array('Elektra', 'Seed', 'Companies', 'ContactInfo'), 'browse')));
            $temporaryGroup->addItem(new Item('Partner Tiers', $navigator->getLink(array('Elektra', 'Seed', 'Companies', 'PartnerTier'), 'browse')));
            $temporaryGroup->addItem(new Item('Warehouse Locations', $navigator->getLink(array('Elektra', 'Seed', 'Companies', 'WarehouseLocation'), 'browse')));

            $siteMenu->addItem($temporaryGroup);
        }
    }

    private function getRequestsMenu()
    {

        $siteLanguage = $this->container->get('siteLanguage');
        $navigator    = $this->container->get('navigator');

        $requestsItem = new Item($siteLanguage->getRequired('menu.requests'), $navigator->getLink(array('Elektra', 'Seed', 'Requests', 'Request'), 'browse'));

        return $requestsItem;
    }

    private function getReportsMenu()
    {

        $siteLanguage = $this->container->get('siteLanguage');

        $reportsItem = new Item($siteLanguage->getRequired('menu.reports'));

        return $reportsItem;
    }

    private function getCompaniesMenu()
    {

        $siteLanguage = $this->container->get('siteLanguage');
        $navigator    = $this->container->get('navigator');

        $companiesItem = new Group($siteLanguage->getRequired('menu.companies'));

        $partner   = new Item($siteLanguage->getRequired('menu.partners'), $navigator->getLink(array('Elektra', 'Seed', 'Companies', 'Partner'), 'browse'));
        $salesTeam = new Item($siteLanguage->getRequired('menu.sales_teams'), $navigator->getLink(array('Elektra', 'Seed', 'Companies', 'SalesTeam'), 'browse'));
        $customer  = new Item($siteLanguage->getRequired('menu.customers'), $navigator->getLink(array('Elektra', 'Seed', 'Companies', 'Customer'), 'browse'));

        $companiesItem->addItem($partner);
        $companiesItem->addItem($salesTeam);
        $companiesItem->addItem($customer);

        return $companiesItem;
    }

    private function getMasterDataMenu()
    {

        $siteLanguage = $this->container->get('siteLanguage');
        $navigator    = $this->container->get('navigator');

        $masterDataItem = new Group($siteLanguage->getRequired('menu.master_data'));

        $seedUnitGroup         = new Group($siteLanguage->getRequired('menu.seed_units'));
        $seedUnit              = new Item($siteLanguage->getRequired('menu.seed_units'), $navigator->getLink(array('Elektra', 'Seed', 'SeedUnits', 'SeedUnit'), 'browse'));
        $seedUnitModel         = new Item($siteLanguage->getRequired('menu.seed_unit_models'), $navigator->getLink(array('Elektra', 'Seed', 'SeedUnits', 'Model'), 'browse'));
        $seedUnitPowerCordType = new Item($siteLanguage->getRequired('menu.seed_unit_power_cord_types'), $navigator->getLink(array('Elektra', 'Seed', 'SeedUnits', 'PowerCordType'), 'browse'));
        $seedUnitGroup->addItem($seedUnit);
        $seedUnitGroup->addItem($seedUnitModel);
        $seedUnitGroup->addItem($seedUnitPowerCordType);

        $trainingGroup = new Group($siteLanguage->getRequired('menu.trainings'));
        $training      = new Item($siteLanguage->getRequired('menu.trainings'), $navigator->getLink(array('Elektra', 'Seed', 'Trainings', 'Training'), 'browse'));
        $registration  = new Item($siteLanguage->getRequired('menu.registrations'), $navigator->getLink(array('Elektra', 'Seed', 'Trainings', 'Registration'), 'browse'));
        $attendance    = new Item($siteLanguage->getRequired('menu.attendances'), $navigator->getLink(array('Elektra', 'Seed', 'Trainings', 'Attendance'), 'browse'));
        $trainingGroup->addItem($training);
        $trainingGroup->addItem($registration);
        $trainingGroup->addItem($attendance);

        $geographicGroup = new Group($siteLanguage->getRequired('menu.geographic'));
        $region          = new Item($siteLanguage->getRequired('menu.regions'), $navigator->getLink(array('Elektra', 'Seed', 'Companies', 'Region'), 'browse'));
        $country         = new Item($siteLanguage->getRequired('menu.countries'), $navigator->getLink(array('Elektra', 'Seed', 'Companies', 'Country'), 'browse'));
        $geographicGroup->addItem($region);
        $geographicGroup->addItem($country);

        $masterDataItem->addItem($seedUnitGroup);
        $masterDataItem->addItem(new Separator());
        $masterDataItem->addItem($trainingGroup);
        $masterDataItem->addItem(new Separator());
        $masterDataItem->addItem($geographicGroup);

        return $masterDataItem;
    }
}