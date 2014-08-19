<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Entity\Companies;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class CompanyLocation
 *
 * @package Elektra\SeedBundle\Entity\Companies
 *
 * @version 0.1-dev
 *
 * @ORM\Entity(repositoryClass="Elektra\SeedBundle\Repositories\Companies\CompanyLocationRepository")
 * @ORM\Table(name="companyLocations")
 */
class CompanyLocation extends PhysicalLocation
{

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $name;

    /**
     * @var Company
     *
     * @ORM\ManyToOne(targetEntity="Company", inversedBy="locations", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="companyId", referencedColumnName="companyId")
     *
     */
    protected $company;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="CompanyPerson", mappedBy="location",fetch="EXTRA_LAZY", cascade={"remove"})
     */
    protected $persons;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    protected $isPrimary;

    /**
     * @var AddressType
     *
     * @ORM\ManyToOne(targetEntity="AddressType", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="addressTypeId", referencedColumnName="addressTypeId")
     */
    protected $addressType;

    /**
     *
     */
    public function __construct()
    {

        parent::__construct();
        $this->persons = new ArrayCollection();
    }

    /**
     * @param Company $company
     */
    public function setCompany($company)
    {

        $this->company = $company;
    }

    /**
     * @return Company
     */
    public function getCompany()
    {

        return $this->company;
    }

    /**
     * @param ArrayCollection $persons
     */
    public function setPersons($persons)
    {

        $this->persons = $persons;
    }

    /**
     * @return ArrayCollection
     */
    public function getPersons()
    {

        return $this->persons;
    }

    /**
     * @param boolean $isPrimary
     */
    public function setIsPrimary($isPrimary)
    {

        $this->isPrimary = $isPrimary;
    }

    /**
     * @return boolean
     */
    public function getIsPrimary()
    {

        return $this->isPrimary;
    }

    /**
     * @param \Elektra\SeedBundle\Entity\Companies\AddressType $addressType
     */
    public function setAddressType($addressType)
    {
        $this->addressType = $addressType;
    }

    /**
     * @return \Elektra\SeedBundle\Entity\Companies\AddressType
     */
    public function getAddressType()
    {
        return $this->addressType;
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
}