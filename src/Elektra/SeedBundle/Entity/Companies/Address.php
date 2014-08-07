<?php

namespace Elektra\SeedBundle\Entity\Companies;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Elektra\SeedBundle\Entity\Auditing\Audit;

/**
 * Class Address
 *
 * @package Elektra\SeedBundle\Entity\Companies
 *
 * @ORM\Entity
 * @ORM\Table("addresses")
 */
class Address
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $addressId;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $state;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $city;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=30)
     */
    protected $postalCode;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $street1;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $street2;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $street3;

    /**
     * @var Country
     *
     * @ORM\ManyToOne(targetEntity="Country", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="countryId", referencedColumnName="countryId")
     *
     */
    protected $country;

    /**
     * @var AddressType
     *
     * @ORM\ManyToOne(targetEntity="AddressType", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="addressTypeId", referencedColumnName="addressTypeId")
     *
     */
    protected $addressType;

    /**
     * @var ArrayCollection
     *
     * @ManyToMany(targetEntity = "Note", fetch="EXTRA_LAZY", cascade={"persist", "remove"})
     * @JoinTable(name = "addresses_notes",
     *      joinColumns = {@JoinColumn(name = "addressId", referencedColumnName = "addressId")},
     *      inverseJoinColumns = {@JoinColumn(name = "noteId", referencedColumnName = "noteId", unique = true)}
     * )
     */
    protected $notes;

    /**
     * @var Audit
     *
     * @ORM\OneToOne(targetEntity="Audit", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="auditId", referencedColumn="auditId")
     */
    protected $audit;

    public function __construct()
    {
        $this->notes = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->addressId;
    }

    /**
     * @return int
     */
    public function getAddressId()
    {
        return $this->addressId;
    }

    /**
     * @param AddressType $addressType
     */
    public function setAddressType($addressType)
    {
        $this->addressType = $addressType;
    }

    /**
     * @return AddressType
     */
    public function getAddressType()
    {
        return $this->addressType;
    }

    /**
     * @param Country $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $postalCode
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;
    }

    /**
     * @return string
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * @param string $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param string $street1
     */
    public function setStreet1($street1)
    {
        $this->street1 = $street1;
    }

    /**
     * @return string
     */
    public function getStreet1()
    {
        return $this->street1;
    }

    /**
     * @param string $street2
     */
    public function setStreet2($street2)
    {
        $this->street2 = $street2;
    }

    /**
     * @return string
     */
    public function getStreet2()
    {
        return $this->street2;
    }

    /**
     * @param string $street3
     */
    public function setStreet3($street3)
    {
        $this->street3 = $street3;
    }

    /**
     * @return string
     */
    public function getStreet3()
    {
        return $this->street3;
    }

    /**
     * @param ArrayCollection $notes
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
    }

    /**
     * @return ArrayCollection
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * @param \Elektra\SeedBundle\Entity\Auditing\Audit $audit
     */
    public function setAudit($audit)
    {
        $this->audit = $audit;
    }

    /**
     * @return \Elektra\SeedBundle\Entity\Auditing\Audit
     */
    public function getAudit()
    {
        return $this->audit;
    }
}