<?php

namespace Elektra\SeedBundle\Entity\Companies;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class GenericLocation
 *
 * @package Elektra\SeedBundle\Entity\Companies
 *
 * @ORM\Entity
 * @ORM\Table(name="genericLocations")
 */
class GenericLocation extends Location
{
    public function __construct()
    {

        parent::__construct();
    }
}