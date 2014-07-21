<?php

namespace Elektra\SeedUnitBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Company
 *
 * @package Elektra\SeedUnitBundle\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="companies")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="companyType", type="string")
 * @ORM\DiscriminatorMap({
 *      "partner" = "Partner",
 *      "customer" = "Customer"
 * })
 */
class Company
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $companyId;

    /**
     * @ORM\OneToMany(targetEntity="CompanyLocation", mappedBy="company")
     */
    protected $locations;
}