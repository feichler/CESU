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
class CompanyLocation extends Location
{

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
}