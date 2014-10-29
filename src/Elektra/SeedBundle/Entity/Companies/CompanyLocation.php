<?php

namespace Elektra\SeedBundle\Entity\Companies;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Entity(repositoryClass="Elektra\SeedBundle\Repository\Companies\CompanyLocationRepository")
 * @ORM\Table(name="locations_company")
 *
 * @ORM\HasLifecycleCallbacks()
 *
 * Unique:
 *      single fields only:
 *          parent.name within company
 *          parent.alias within company
 */
class CompanyLocation extends AbstractPhysicalLocation
{

    /**
     * @var Company
     *
     * @ORM\ManyToOne(targetEntity="Elektra\SeedBundle\Entity\Companies\Company", inversedBy="locations",
     *                                                                            fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="companyId", referencedColumnName="companyId")
     *
     */
    protected $company;

    /**
     * @var Collection CompanyPerson[]
     *
     * @ORM\OneToMany(targetEntity="Elektra\SeedBundle\Entity\Companies\CompanyPerson", mappedBy="location",
     * fetch="EXTRA_LAZY", cascade={"remove", "persist"})
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

    /*************************************************************************
     * Getters / Setters
     *************************************************************************/

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
     * @param CompanyPerson $person
     */
    public function addPerson(CompanyPerson $person)
    {

        $this->getPersons()->add($person);
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

    /*************************************************************************
     * Other methods
     *************************************************************************/

    /**
     * @return CompanyPerson
     */
    public function getPrimaryPerson()
    {

        $primaryPerson = $this->getPersons()->matching(Criteria::create()->where(Criteria::expr()
                                                                                         ->eq("isPrimary", true))
                                                               ->setMaxResults(1))->first();

        return $primaryPerson;
    }

    /*************************************************************************
     * EntityInterface
     *************************************************************************/

    // none

    /*************************************************************************
     * Lifecycle callbacks
     *************************************************************************/

    // none
}