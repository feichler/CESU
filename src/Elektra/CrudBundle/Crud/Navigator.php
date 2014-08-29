<?php

namespace Elektra\CrudBundle\Crud;

use Elektra\SeedBundle\Definition\Companies\CompanyLocationDefinition;
use Elektra\SeedBundle\Definition\Companies\CompanyPersonDefinition;
use Elektra\SeedBundle\Definition\Companies\CountryDefinition;
use Elektra\SeedBundle\Definition\Companies\CustomerDefinition;
use Elektra\SeedBundle\Definition\Companies\PartnerDefinition;
use Elektra\SeedBundle\Definition\Companies\PartnerTierDefinition;
use Elektra\SeedBundle\Definition\Companies\RegionDefinition;
use Elektra\SeedBundle\Definition\Companies\SalesTeamDefinition;
use Elektra\SeedBundle\Definition\SeedUnits\ModelDefinition;
use Elektra\SeedBundle\Definition\SeedUnits\PowerCordTypeDefinition;
use Elektra\SeedBundle\Definition\SeedUnits\SeedUnitDefinition;
use Symfony\Component\Routing\RouterInterface;

final class Navigator
{

    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @var array
     */
    protected $definitions;

    /**
     * @var array
     */
    protected $definitionsMap;

    /**
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {

        $this->router         = $router;
        $this->definitions    = array();
        $this->definitionsMap = array();
        // URGENT add the single definitions from the seedbundle

        $this->addDefinition(new ModelDefinition());
        $this->addDefinition(new PowerCordTypeDefinition());
        $this->addDefinition(new SeedUnitDefinition());

        $this->addDefinition(new RegionDefinition());
        $this->addDefinition(new CountryDefinition());

        $this->addDefinition(new PartnerDefinition());
        $this->addDefinition(new CustomerDefinition());
        $this->addDefinition(new SalesTeamDefinition());
        $this->addDefinition(new CompanyLocationDefinition());
        $this->addDefinition(new CompanyPersonDefinition());

        $this->addDefinition(new PartnerTierDefinition());
    }

    /**
     * @param Definition $definition
     */
    protected function addDefinition(Definition $definition)
    {

        // add the definition itself to the list
        $this->definitions[$definition->getKey()] = $definition;

        // if the definition is routeable, populate the lookup list
        if ($definition->hasRoute()) {
            $this->definitionsMap[$definition->getRouteSingular()] = $definition->getKey();
            if ($definition->isRoot()) {
                $this->definitionsMap[$definition->getRoutePlural()] = $definition->getKey();
            }
        }

        // also add the entity class name to the map
        $this->definitionsMap[$definition->getClassEntity()] = $definition->getKey();
    }

    /**
     * @return array Definitions[]
     */
    public function getDefinitions()
    {

        return $this->definitions;
    }

    /**
     * @param mixed       $vendor
     * @param string|null $bundle
     * @param string|null $group
     * @param string|null $name
     *
     * @return Definition
     * @throws \InvalidArgumentException
     */
    public function getDefinition($vendor, $bundle = null, $group = null, $name = null)
    {

        if ($bundle !== null && $group !== null && $name !== null) {
            if (is_string($vendor)) {
                $vendor = Definition::generateKey($vendor, $bundle, $group, $name);
            }
        } else {
            if (is_string($vendor)) {
                $vendor = $vendor;
            } else if (is_array($vendor)) {
                $vendor = Definition::generateKey($vendor[0], $vendor[1], $vendor[2], $vendor[3]);
            } else if ($vendor instanceof Definition) {
                $vendor = $vendor->getKey();
            } else {
                throw new \InvalidArgumentException('Cannot get a key from the given parameters');
            }
        }

        if (array_key_exists($vendor, $this->definitions)) {
            return $this->definitions[$vendor];
        } else if (array_key_exists($vendor, $this->definitionsMap)) {
            return $this->definitions[$this->definitionsMap[$vendor]];
        }

        throw new \InvalidArgumentException('Cannot get a definition from the given parameters');
    }

    // URGENT get route
    // URGENT get link

    /**
     * @param Definition $definition
     * @param array      $parameters
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getBrowseLink(Definition $definition, array $parameters = array())
    {

        if (!$definition->isRoot()) {
            throw new \InvalidArgumentException('Given Definition (' . $definition->getKey() . ') is no root route');
        }

        return $this->getLink($definition, 'browse', $parameters);

        //        $route = $definition->getRoutePlural();
        //
        //        return $this->router->generate($route, $parameters);
    }

    public function getLink($definition, $action, $parameters = array())
    {

        $definition = $this->getDefinition($definition);

        if ($action == 'browse') {
            $routeName = $definition->getRoutePlural();
        } else {
            $routeName = $definition->getRouteSingular() . '.' . $action;
        }

        return $this->getLinkFromRoute($routeName, $parameters);
    }

    public function getLinkFromRoute($routeName, $parameters = array())
    {

        $link = $this->router->generate($routeName, $parameters);

        return $link;
    }
}