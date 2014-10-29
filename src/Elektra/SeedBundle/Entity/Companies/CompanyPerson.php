<?php

namespace Elektra\SeedBundle\Entity\Companies;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Elektra\SeedBundle\Repository\Companies\CompanyPersonRepository")
 * @ORM\Table("persons_company")
 *
 * @ORM\HasLifecycleCallbacks()
 *
 * Unique: nothing
 */
class CompanyPerson extends Person
{

    /**
     * @var CompanyLocation
     *
     * @ORM\ManyToOne(targetEntity="Elektra\SeedBundle\Entity\Companies\CompanyLocation", inversedBy="persons",
     * fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="locationId", referencedColumnName="locationId")
     *
     */
    protected $location;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $isPrimary;

    /**
     * Constructor
     */
    public function __construct()
    {

        parent::__construct();
    }

    /*************************************************************************
     * Getters / Setters
     *************************************************************************/

    /**
     * @param CompanyLocation $location
     */
    public function setLocation($location)
    {

        $this->location = $location;
    }

    /**
     * @return CompanyLocation
     */
    public function getLocation()
    {

        return $this->location;
    }

    /**
     * @param bool $isPrimary
     */
    public function setIsPrimary($isPrimary)
    {

        $this->isPrimary = $isPrimary;
    }

    /**
     * @return bool
     */
    public function getIsPrimary()
    {

        return $this->isPrimary;
    }

    /*************************************************************************
     * EntityInterface
     *************************************************************************/

    // nothing

    /*************************************************************************
     * Lifecycle callbacks
     *************************************************************************/

    // none
}