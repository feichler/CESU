<?php

namespace Elektra\SeedBundle\Entity\Company;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Address
 *
 * @package Elektra\SeedBundle\Entity\Company
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
     * @ORM\Column(type="string", length=50)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50)
     */
    protected $type;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $country;

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
     * @var string
     *
     * @ORM\Column(type="boolean")
     */
    protected $isPrimary;

    /**
     * @var Location
     *
     * @ORM\ManyToOne(targetEntity="Location", inversedBy="addresses",fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="locationId",referencedColumnName="locationId")
     *
     */
    protected $location;

    public function __construct()
    {
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
     * @param string $country
     */
    public function setCountry($country)
    {

        $this->country = $country;
    }

    /**
     * @return string
     */
    public function getCountry()
    {

        return $this->country;
    }

    /**
     * @param string $isPrimary
     */
    public function setIsPrimary($isPrimary)
    {

        $this->isPrimary = $isPrimary;
    }

    /**
     * @return string
     */
    public function getIsPrimary()
    {

        return $this->isPrimary;
    }

    /**
     * @param \Elektra\SeedBundle\Entity\Company\Location $location
     */
    public function setLocation($location)
    {

        $this->location = $location;
    }

    /**
     * @return \Elektra\SeedBundle\Entity\Company\Location
     */
    public function getLocation()
    {

        return $this->location;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {

        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {

        return $this->name;
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
     * @param string $type
     */
    public function setType($type)
    {

        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {

        return $this->type;
    }
}