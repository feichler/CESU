<?php

namespace Elektra\SeedUnitBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Person
 *
 * @package Elektra\SeedUnitBundle\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="persons")
 */
class Person
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $personId;

    /**
     * @ORM\ManyToOne(targetEntity="CompanyLocation", inversedBy="persons")
     * @ORM\JoinColumn(name="locationId", referencedColumnName="locationId")
     */
    protected $location;

    /**
     * @ORM\OneToMany(targetEntity="ContactInfo",mappedBy="person")
     *
     */
    protected $contactInfos;
}