<?php

namespace Elektra\SeedUnitBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class SeedUnit
 *
 * @package Elektra\SeedUnitBundle\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="seed_units")
 */
class SeedUnit
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $seedUnitId;

    /**
     * @ORM\ManyToOne(targetEntity="SeedUnitModel",inversedBy="seedUnits")
     * @ORM\JoinColumn(name="seedUnitModelId",referencedColumnName="seedUnitModelId")
     */
    protected $seedUnitType;
}