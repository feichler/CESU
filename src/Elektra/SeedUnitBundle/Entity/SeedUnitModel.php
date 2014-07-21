<?php

namespace Elektra\SeedUnitBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class SeedUnitModel
 *
 * @package Elektra\SeedUnitBundle\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="seed_unit_models")
 */
class SeedUnitModel
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $seedUnitModelId;

    /**
     * @ORM\OneToMany(targetEntity="SeedUnit",mappedBy="seedUnitType")
     */
    protected $seedUnits;
}