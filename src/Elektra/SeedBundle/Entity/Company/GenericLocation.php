<?php

namespace Elektra\SeedBundle\Entity\Company;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class GenericLocation
 *
 * @package Elektra\SeedBundle\Entity\Company
 *
 * @ORM\Entity
 * @ORM\Table(name="location_generic")
 */
class GenericLocation extends Location
{

    public function __construct()
    {

        parent::__construct();
    }
}