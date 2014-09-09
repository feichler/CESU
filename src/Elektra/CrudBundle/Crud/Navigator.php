<?php

namespace Elektra\CrudBundle\Crud;

use Elektra\SeedBundle\Definition\Auditing\AuditDefinition;
use Elektra\SeedBundle\Definition\Companies\AddressDefinition;
use Elektra\SeedBundle\Definition\Companies\AddressTypeDefinition;
use Elektra\SeedBundle\Definition\Companies\CompanyDefinition;
use Elektra\SeedBundle\Definition\Companies\CompanyLocationDefinition;
use Elektra\SeedBundle\Definition\Companies\CompanyPersonDefinition;
use Elektra\SeedBundle\Definition\Companies\ContactInfoDefinition;
use Elektra\SeedBundle\Definition\Companies\ContactInfoTypeDefinition;
use Elektra\SeedBundle\Definition\Companies\CountryDefinition;
use Elektra\SeedBundle\Definition\Companies\CustomerDefinition;
use Elektra\SeedBundle\Definition\Companies\GenericLocationDefinition;
use Elektra\SeedBundle\Definition\Companies\LocationDefinition;
use Elektra\SeedBundle\Definition\Companies\PartnerDefinition;
use Elektra\SeedBundle\Definition\Companies\PartnerTierDefinition;
use Elektra\SeedBundle\Definition\Companies\PartnerTypeDefinition;
use Elektra\SeedBundle\Definition\Companies\PersonDefinition;
use Elektra\SeedBundle\Definition\Companies\RegionDefinition;
use Elektra\SeedBundle\Definition\Companies\WarehouseLocationDefinition;
use Elektra\SeedBundle\Definition\Events\EventDefinition;
use Elektra\SeedBundle\Definition\Events\EventTypeDefinition;
use Elektra\SeedBundle\Definition\Events\UnitStatusDefinition;
use Elektra\SeedBundle\Definition\Notes\NoteDefinition;
use Elektra\SeedBundle\Definition\Requests\RequestDefinition;
use Elektra\SeedBundle\Definition\SeedUnits\ModelDefinition;
use Elektra\SeedBundle\Definition\SeedUnits\PowerCordTypeDefinition;
use Elektra\SeedBundle\Definition\SeedUnits\SeedUnitDefinition;
use Elektra\SeedBundle\Definition\Trainings\AttendanceDefinition;
use Elektra\SeedBundle\Definition\Trainings\RegistrationDefinition;
use Elektra\SeedBundle\Definition\Trainings\TrainingDefinition;
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
        $this->addDefinition(new AuditDefinition());
        $this->addDefinition(new NoteDefinition());

        $this->addDefinition(new AddressDefinition());
        $this->addDefinition(new AddressTypeDefinition());
        $this->addDefinition(new CompanyDefinition());
        $this->addDefinition(new CompanyLocationDefinition());
        $this->addDefinition(new CompanyPersonDefinition());
        $this->addDefinition(new ContactInfoDefinition());
        $this->addDefinition(new ContactInfoTypeDefinition());
        $this->addDefinition(new CountryDefinition());
        $this->addDefinition(new CustomerDefinition());
        $this->addDefinition(new GenericLocationDefinition());
        $this->addDefinition(new LocationDefinition());
        $this->addDefinition(new PartnerDefinition());
        $this->addDefinition(new PartnerTierDefinition());
        $this->addDefinition(new PartnerTypeDefinition());
        $this->addDefinition(new PersonDefinition());
        $this->addDefinition(new RegionDefinition());
        $this->addDefinition(new WarehouseLocationDefinition());

        $this->addDefinition(new RequestDefinition());

        $this->addDefinition(new ModelDefinition());
        $this->addDefinition(new PowerCordTypeDefinition());
        $this->addDefinition(new SeedUnitDefinition());

        $this->addDefinition(new AttendanceDefinition());
        $this->addDefinition(new RegistrationDefinition());
        $this->addDefinition(new TrainingDefinition());

        $this->addDefinition(new EventTypeDefinition());
        $this->addDefinition(new UnitStatusDefinition());
        $this->addDefinition(new EventDefinition());

        $this->addDefinition(new \Elektra\SeedBundle\Definition\Imports\SeedUnitDefinition());
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
                $key = Definition::generateKey($vendor, $bundle, $group, $name);
            }
        } else {
            if (is_string($vendor)) {
                $key = $vendor;
            } else if (is_array($vendor)) {
                $key = Definition::generateKey($vendor[0], $vendor[1], $vendor[2], $vendor[3]);
            } else if ($vendor instanceof Definition) {
                $key = $vendor->getKey();
            } else {
                throw new \InvalidArgumentException('Cannot get a key from the given parameters');
            }
        }

        if (array_key_exists($key, $this->definitions)) {
            return $this->definitions[$key];
        } else if (array_key_exists($key, $this->definitionsMap)) {
            return $this->definitions[$this->definitionsMap[$key]];
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
    }

    /**
     * @param mixed  $definition
     * @param string $action
     * @param array  $parameters
     *
     * @return string
     */
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

    /**
     * @param string $routeName
     * @param array  $parameters
     *
     * @return string
     */
    public function getLinkFromRoute($routeName, $parameters = array())
    {

        $link = $this->router->generate($routeName, $parameters);

        return $link;
    }
}