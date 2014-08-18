<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SiteBundle\Navigator;

use Symfony\Component\Routing\RouterInterface;

/**
 * Class Navigator
 *
 * @package Elektra\SiteBundle\Navigator
 *
 * @version 0.1-dev
 */
class Navigator
{

    /**
     * @var array
     */
    protected $definitions;

    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {

        $this->router      = $router;
        $this->definitions = array();

        $this->loadDefinitions();
    }

    /**
     *
     */
    protected function loadDefinitions()
    {

        $this->addDefinition('Elektra', 'Seed', 'SeedUnits', 'SeedUnit');
        $this->addDefinition('Elektra', 'Seed', 'SeedUnits', 'SeedUnitModel');
        $this->addDefinition('Elektra', 'Seed', 'SeedUnits', 'SeedUnitPowerCordType');
        $this->addDefinition('Elektra', 'Seed', 'Companies', 'CompanyLocation');
        $this->addDefinition('Elektra', 'Seed', 'Companies', 'CompanyPerson');
        $this->addDefinition('Elektra', 'Seed', 'Companies', 'ContactInfo');
        $this->addDefinition('Elektra', 'Seed', 'Companies', 'Country');
        $this->addDefinition('Elektra', 'Seed', 'Companies', 'Customer');
        $this->addDefinition('Elektra', 'Seed', 'Companies', 'LocationAddress');
        $this->addDefinition('Elektra', 'Seed', 'Companies', 'Partner');
        $this->addDefinition('Elektra', 'Seed', 'Companies', 'PartnerTier');
        $this->addDefinition('Elektra', 'Seed', 'Companies', 'Region');
        $this->addDefinition('Elektra', 'Seed', 'Companies', 'SalesTeam');
        $this->addDefinition('Elektra', 'Seed', 'Companies', 'WarehouseLocation');
        $this->addDefinition('Elektra', 'Seed', 'Trainings', 'Attendance');
        $this->addDefinition('Elektra', 'Seed', 'Trainings', 'Registration');
        $this->addDefinition('Elektra', 'Seed', 'Trainings', 'Training');
        $this->addDefinition('Elektra', 'Seed', 'Requests', 'Request');
    }

    /**
     * @param string $vendor
     * @param string $bundle
     * @param string $group
     * @param string $name
     */
    protected function addDefinition($vendor, $bundle, $group, $name)
    {

        $definition = new Definition($vendor, $this->checkBundle($bundle), $group, $name);
        $definition->setKey($this->getKey($definition));
        //$definition->show();
        $this->definitions[$this->getKey($definition)] = $definition;
    }

    /**
     * @param Definition $definition
     *
     * @return string
     */
    private function getKey(Definition $definition)
    {

        return $this->calculateKey($definition->getVendor(), $definition->getBundle(), $definition->getGroup(), $definition->getName());
    }

    /**
     * @param string $vendor
     * @param string $bundle
     * @param string $group
     * @param string $name
     *
     * @return string
     */
    private function calculateKey($vendor, $bundle, $group, $name)
    {

        $key = $vendor . '-' . $this->checkBundle($bundle) . '-' . $group . '-' . $name;

        return $key;
    }

    /**
     * @return array
     */
    public function getDefinitions()
    {

        return $this->definitions;
    }

    /**
     * @param string $key
     *
     * @return Definition
     * @throws \RuntimeException
     */
    public function getDefinitionByKey($key)
    {

        if (array_key_exists($key, $this->definitions)) {
            return $this->definitions[$key];
        }

        throw new \RuntimeException('No definition for the key "' . $key . '" found');
    }

    /**
     * @param string $vendor
     * @param string $bundle
     * @param string $group
     * @param string $name
     *
     * @return Definition
     */
    public function getDefinition($vendor, $bundle, $group, $name)
    {

        return $this->getDefinitionByKey($this->calculateKey($vendor, $bundle, $group, $name));
    }

    /**
     * @param string $bundle
     *
     * @return string
     */
    private function checkBundle($bundle)
    {

        if (strpos($bundle, 'Bundle') === false) {
            $bundle = $bundle . 'Bundle';
        }

        return $bundle;
    }

    /**
     * @param mixed  $definition
     * @param string $type
     * @param array  $parameters
     *
     * @return string
     */
    public function getLink($definition, $type = '', $parameters = array())
    {

        $route = $this->getRoute($definition, $type);
        $link  = $this->router->generate($route, $parameters);

        return $link;
    }

    /**
     * @param mixed  $definition
     * @param string $type
     *
     * @return string
     */
    public function getRoute($definition, $type = '')
    {

        $definition = $this->getDefinitionFromMixed($definition);

        if ($type == '') {
            $type = 'browse';
        }

        $route = $definition->getRouteNamePrefix() . '_' . $type;

        return $route;
    }

    /**
     * @param mixed $mixed
     *
     * @return Definition
     * @throws \RuntimeException
     */
    private function getDefinitionFromMixed($mixed)
    {

        if ($mixed instanceof Definition) {
            // this is fine - no need to convert anything else to a Definition object
            $definition = $mixed;
        } else if (is_array($mixed)) {
            $definition = $this->getDefinition($mixed[0], $mixed[1], $mixed[2], $mixed[3]);
        } else if (is_string($mixed)) {
            $definition = $this->getDefinitionByKey($mixed);
        }

        if ($definition === null) {
            throw new \RuntimeException('Cannot find the required definition');
        }

        return $definition;
    }
}