<?php

namespace Elektra\SeedBundle\Entity\Companies;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Customer
 *
 * @package Elektra\SeedBundle\Entity\Companies
 *
 * @ORM\Entity
 * @ORM\Table(name="customerCompanies")
 */
class Customer extends Company
{
    public function __construct()
    {
        parent::__construct();
    }
}