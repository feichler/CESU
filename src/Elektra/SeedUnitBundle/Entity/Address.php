<?php

namespace Elektra\SeedUnitBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Address
 *
 * @package Elektra\SeedUnitBundle\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="addresses")
 */
class Address
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $addressId;

    /**
     * @ORM\ManyToOne(targetEntity="Location", inversedBy="addresses")
     * @ORM\JoinColumn(name="locationId",referencedColumnName="locationId")
     */
    protected $location;
}