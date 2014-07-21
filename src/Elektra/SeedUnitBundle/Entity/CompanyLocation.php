<?php

namespace Elektra\SeedUnitBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class CompanyLocation
 *
 * @package Elektra\SeedUnitBundle\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="company_locations")
 */
class CompanyLocation extends Location
{

    /**
     * @ORM\ManyToOne(targetEntity="Company", inversedBy="locations")
     * @ORM\JoinColumn(name="companyId", referencedColumnName="companyId")
     */
    protected $company;

    /**
     * @ORM\OneToMany(targetEntity="Person", mappedBy="location")
     */
    protected $persons;
}