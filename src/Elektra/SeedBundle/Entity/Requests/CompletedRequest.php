<?php

namespace Elektra\SeedBundle\Entity\Requests;

use Doctrine\ORM\Mapping as ORM;
use Elektra\SeedBundle\Entity\Companies\CompanyLocation;
use Elektra\SeedBundle\Entity\Companies\CompanyPerson;
use Doctrine\Common\Collections\ArrayCollection;
use Elektra\SeedBundle\Entity\SeedUnits\SeedUnit;

/**
 * Class RequestCompletion
 *
 * @package Elektra\SeedBundle\Entity\Requests
 *
 * @ORM\Entity
 * @ORM\Table(name="completedRequests")
 */
class CompletedRequest extends Request
{
    /**
     * @var CompanyPerson
     *
     * @ORM\ManyToOne(targetEntity="Elektra\SeedBundle\Entity\Companies\CompanyPerson", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="requesterPersonId", referencedColumnName="personId")
     */
    protected $requesterPerson;

    /**
     * @var CompanyPerson
     *
     * @ORM\ManyToOne(targetEntity="Elektra\SeedBundle\Entity\Companies\CompanyPerson", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="receiverPersonId", referencedColumnName="personId")
     */
    protected $receiverPerson;

    /**
     * @var CompanyLocation
     *
     * @ORM\ManyToOne(targetEntity="Elektra\SeedBundle\Entity\Companies\CompanyLocation", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="shippingLocationId", referencedColumnName="locationId")
     */
    protected $shippingLocation;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Elektra\SeedBundle\Entity\SeedUnits\SeedUnit", mappedBy="requestCompletion", fetch="EXTRA_LAZY")
     */
    protected $seedUnits;

    public function __construct()
    {
    }

    /**
     * @param \Elektra\SeedBundle\Entity\Companies\CompanyPerson $receiverPerson
     */
    public function setReceiverPerson($receiverPerson)
    {
        $this->receiverPerson = $receiverPerson;
    }

    /**
     * @return \Elektra\SeedBundle\Entity\Companies\CompanyPerson
     */
    public function getReceiverPerson()
    {
        return $this->receiverPerson;
    }

    /**
     * @param \Elektra\SeedBundle\Entity\Companies\CompanyPerson $requesterPerson
     */
    public function setRequesterPerson($requesterPerson)
    {
        $this->requesterPerson = $requesterPerson;
    }

    /**
     * @return \Elektra\SeedBundle\Entity\Companies\CompanyPerson
     */
    public function getRequesterPerson()
    {
        return $this->requesterPerson;
    }

    /**
     * @param \Elektra\SeedBundle\Entity\Companies\CompanyLocation $shippingLocation
     */
    public function setShippingLocation($shippingLocation)
    {
        $this->shippingLocation = $shippingLocation;
    }

    /**
     * @return \Elektra\SeedBundle\Entity\Companies\CompanyLocation
     */
    public function getShippingLocation()
    {
        return $this->shippingLocation;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $seedUnits
     */
    public function setSeedUnits($seedUnits)
    {
        $this->seedUnits = $seedUnits;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getSeedUnits()
    {
        return $this->seedUnits;
    }
}