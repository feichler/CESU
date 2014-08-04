<?php

namespace Elektra\SeedBundle\Entity\Company;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Customer
 *
 * @package Elektra\SeedBundle\Entity\Company
 *
 * @ORM\Entity
 * @ORM\Table(name="companies_customer")
 */
class Customer extends Company
{

    public function __construct()
    {

        parent::__construct();
    }
}