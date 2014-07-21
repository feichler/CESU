<?php

namespace Elektra\SeedUnitBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Location
 *
 * @package Elektra\SeedUnitBundle\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="locations")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="locationType", type="string")
 * @ORM\DiscriminatorMap({
 *      "company" = "CompanyLocation",
 *      "generic" = "GenericLocation",
 *      "warehouse" = "WarehouseLocation",
 * })
 */
class Location
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $locationId;

    /**
     * @ORM\OneToMany(targetEntity="Address",mappedBy="location")
     */
    protected $addresses;
}