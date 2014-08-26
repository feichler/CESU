<?php

namespace Elektra\CrudBundle\Navigator;

use Elektra\CrudBundle\Definition\Definition;
use Elektra\SeedBundle\Definition\SeedUnits\ModelDefinition;
use Symfony\Component\Routing\RouterInterface;

class Navigator
{

    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @var array
     */
    protected $definitions;

    public function __construct(RouterInterface $router)
    {

        $this->router      = $router;
        $this->definitions = array();

        $this->addDefinitionPlain('Elektra', 'Seed', 'SeedUnits', 'SeedUnit'); //
        //        $this->addDefinitionPlain('Elektra', 'Seed', 'SeedUnits', 'Model'); //
        $this->addDefinition(new ModelDefinition());
        $this->addDefinitionPlain('Elektra', 'Seed', 'SeedUnits', 'PowerCordType'); //
        $this->addDefinitionPlain('Elektra', 'Seed', 'Companies', 'CompanyLocation'); //
        $this->addDefinitionPlain('Elektra', 'Seed', 'Companies', 'CompanyPerson');
        $this->addDefinitionPlain('Elektra', 'Seed', 'Companies', 'ContactInfo');
        $this->addDefinitionPlain('Elektra', 'Seed', 'Companies', 'Country');
        $this->addDefinitionPlain('Elektra', 'Seed', 'Companies', 'Customer');
        $this->addDefinitionPlain('Elektra', 'Seed', 'Companies', 'Partner');
        $this->addDefinitionPlain('Elektra', 'Seed', 'Companies', 'PartnerTier');
        $this->addDefinitionPlain('Elektra', 'Seed', 'Companies', 'Region');
        $this->addDefinitionPlain('Elektra', 'Seed', 'Companies', 'SalesTeam');
        $this->addDefinitionPlain('Elektra', 'Seed', 'Companies', 'WarehouseLocation');
        $this->addDefinitionPlain('Elektra', 'Seed', 'Requests', 'RequestCompletion');
        $this->addDefinitionPlain('Elektra', 'Seed', 'Trainings', 'Attendance');
        $this->addDefinitionPlain('Elektra', 'Seed', 'Trainings', 'Registration');
        $this->addDefinitionPlain('Elektra', 'Seed', 'Trainings', 'Training');
        $this->addDefinitionPlain('Elektra', 'Seed', 'Requests', 'Request');
        // CHECK add custom values for specific definitions?
    }

    public function getDefinitions()
    {

        return $this->definitions;
    }

    public function getKeyFromDefinition(Definition $definition)
    {

        $key = $this->getKey($definition->getVendor(), $definition->getBundle(), $definition->getGroup(), $definition->getName());

        return $key;
    }

    public function getKey($vendor, $bundle, $group, $name)
    {

        $key = $vendor . '-' . (strpos($bundle, 'Bundle') === false ? $bundle . 'Bundle' : $bundle) . '-' . $group . '-' . $name;

        return $key;
    }

    private function addDefinition(Definition $definition)
    {

        $definition->setKey($this->getKeyFromDefinition($definition));
        $this->definitions[$definition->getKey()] = $definition;
    }

    private function addDefinitionPlain($vendor, $bundle, $group, $name)
    {

        $definition = new Definition($vendor, $bundle, $group, $name);
        $definition->setKey($this->getKeyFromDefinition($definition));
        $this->definitions[$definition->getKey()] = $definition;
    }

    public function getDefinition($vendor, $bundle, $group, $name)
    {

        return $this->getDefinitionByKey($this->getKey($vendor, $bundle, $group, $name));
    }

    public function getDefinitionByKey($key)
    {

        if (array_key_exists($key, $this->definitions)) {
            return $this->definitions[$key];
        }

        throw new \RuntimeException('No definition for the key "' . $key . '" found');
    }

    public function getRoute($definition, $type = '')
    {

        $definition = $this->getDefinitionFromMixed($definition);

        if ($type == '') {
            $type = 'browse';
        }

        $route = $definition->getPrefixRoute() . '_' . $type;

        return $route;
    }

    public function getLink($definition, $type = '', $parameters = array())
    {

        $route = $this->getRoute($definition, $type);
        $link  = $this->router->generate($route, $parameters);

        return $link;
    }

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