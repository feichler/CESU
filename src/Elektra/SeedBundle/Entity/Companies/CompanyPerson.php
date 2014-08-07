<?php

namespace Elektra\SeedBundle\Entity\Companies;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class CompanyPerson
 *
 * @package Elektra\SeedBundle\Entity\Companies
 *
 * @ORM\Entity
 * @ORM\Table("companyPersons")
 */
class CompanyPerson extends Person
{
    /**
     * @var Location
     *
     * @ORM\ManyToOne(targetEntity="CompanyLocation", inversedBy="persons", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="locationId", referencedColumnName="locationId")
     *
     */
    protected $location;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Attendance", mappedBy="person", fetch="EXTRA_LAZY")
     */
    protected $attendances;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Registration", mappedBy="person", fetch="EXTRA_LAZY")
     */
    protected $registrations;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    protected $isPrimary;

    public function __construct()
    {
        $this->attendances = new ArrayCollection();
        $this->registrations = new ArrayCollection();
    }

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
     * @param ArrayCollection $attendances
     */
    public function setAttendances($attendances)
    {
        $this->attendances = $attendances;
    }

    /**
     * @return ArrayCollection
     */
    public function getAttendances()
    {
        return $this->attendances;
    }

    /**
     * @param ArrayCollection $registrations
     */
    public function setRegistrations($registrations)
    {
        $this->registrations = $registrations;
    }

    /**
     * @return ArrayCollection
     */
    public function getRegistrations()
    {
        return $this->registrations;
    }

    /**
     * @param int $isPrimary
     */
    public function setIsPrimary($isPrimary)
    {
        $this->isPrimary = $isPrimary;
    }

    /**
     * @return int
     */
    public function getIsPrimary()
    {
        return $this->isPrimary;
    }
}