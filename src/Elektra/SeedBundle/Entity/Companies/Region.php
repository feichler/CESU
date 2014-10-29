<?php

namespace Elektra\SeedBundle\Entity\Companies;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Elektra\SeedBundle\Entity\AbstractAuditableEntity;
use Elektra\SeedBundle\Entity\Auditing\Audit;

/**
 * @ORM\Entity(repositoryClass="Elektra\SeedBundle\Repository\Companies\RegionRepository")
 * @ORM\Table(name="regions")
 *
 * Unique:
 *      single fields only:
 *          name
 */
class Region extends AbstractAuditableEntity
{

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer", options={"unsigned" = true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $regionId;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50, unique=true)
     */
    protected $name;

    /**
     * @var Collection Country[]
     *
     * @ORM\OneToMany(targetEntity="Elektra\SeedBundle\Entity\Companies\Country", mappedBy="region",
     *                                                                            fetch="EXTRA_LAZY",
     *                                                                            cascade={"remove"})
     */
    protected $countries;

    /**
     * @var Collection Audit[]
     *
     * @ORM\ManyToMany(targetEntity = "Elektra\SeedBundle\Entity\Auditing\Audit", fetch="EXTRA_LAZY",
     *                              cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"timestamp" = "DESC"})
     * @ORM\JoinTable(name = "regions_audits",
     *      joinColumns = {@ORM\JoinColumn(name = "regionId", referencedColumnName = "regionId")},
     *      inverseJoinColumns = {@ORM\JoinColumn(name = "auditId", referencedColumnName = "auditId", unique = true,
     *      onDelete="CASCADE")}
     * )
     */
    protected $audits;

    /**
     * Constructor
     */
    public function __construct()
    {

        parent::__construct();

        $this->countries = new ArrayCollection();
    }

    /*************************************************************************
     * Getters / Setters
     *************************************************************************/

    /**
     * @return int
     */
    public function getRegionId()
    {

        return $this->regionId;
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
     * @param Collection Country[] $countries
     */
    public function setCountries($countries)
    {

        $this->countries = $countries;
    }

    /**
     * @param Country $country
     */
    public function addCountry(Country $country)
    {

        $this->getCountries()->add($country);
    }

    /**
     * @return Collection Country[]
     */
    public function getCountries()
    {

        return $this->countries;
    }

    /*************************************************************************
     * EntityInterface
     *************************************************************************/

    /**
     * {@inheritdoc}
     */
    public function getId()
    {

        return $this->getRegionId();
    }

    /**
     * {@inheritdoc}
     */
    public function getDisplayName()
    {

        return $this->getName();
    }

    /*************************************************************************
     * Lifecycle callbacks
     *************************************************************************/

    // none
}