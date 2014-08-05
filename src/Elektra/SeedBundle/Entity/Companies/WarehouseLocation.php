<?php

namespace Elektra\SeedBundle\Entity\Companies;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class WarehouseLocation
 *
 * @package Elektra\SeedBundle\Entity\Companies
 *
 * @ORM\Entity
 * @ORM\Table(name="warehouseLocations")
 */
class WarehouseLocation extends Location
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=20, unique=true)
     */
    protected $locationIdentifier;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param string $locationIdentifier
     */
    public function setLocationIdentifier($locationIdentifier)
    {
        $this->locationIdentifier = $locationIdentifier;
    }

    /**
     * @return string
     */
    public function getLocationIdentifier()
    {
        return $this->locationIdentifier;
    }
}