<?php

namespace Elektra\SeedBundle\Entity\Company;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class CompanyLocation
 *
 * @package Elektra\SeedBundle\Entity\Company
 *
 * @ORM\Entity
 * @ORM\Table(name="location_companies")
 */
class CompanyLocation extends Location
{

    public function __construct()
    {

        parent::__construct();
    }
}