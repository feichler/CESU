<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

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
 * @version 0.1-dev
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
     * @ORM\OneToMany(targetEntity="Elektra\SeedBundle\Entity\SeedUnits\SeedUnit", mappedBy="request", fetch="EXTRA_LAZY")
     */
    protected $seedUnits;

    /**
     *
     */
    public function __construct()
    {

        $this->seedUnits = new ArrayCollection();
    }

    /**
     * @param CompanyPerson $receiverPerson
     */
    public function setReceiverPerson($receiverPerson)
    {

        $this->receiverPerson = $receiverPerson;
    }

    /**
     * @return CompanyPerson
     */
    public function getReceiverPerson()
    {

        return $this->receiverPerson;
    }

    /**
     * @param CompanyPerson $requesterPerson
     */
    public function setRequesterPerson($requesterPerson)
    {

        $this->requesterPerson = $requesterPerson;
    }

    /**
     * @return CompanyPerson
     */
    public function getRequesterPerson()
    {

        return $this->requesterPerson;
    }

    /**
     * @param CompanyLocation $shippingLocation
     */
    public function setShippingLocation($shippingLocation)
    {

        $this->shippingLocation = $shippingLocation;
    }

    /**
     * @return \CompanyLocation
     */
    public function getShippingLocation()
    {

        return $this->shippingLocation;
    }

    /**
     * @param ArrayCollection $seedUnits
     */
    public function setSeedUnits($seedUnits)
    {

        $this->seedUnits = $seedUnits;
    }

    /**
     * @return ArrayCollection
     */
    public function getSeedUnits()
    {

        return $this->seedUnits;
    }
}