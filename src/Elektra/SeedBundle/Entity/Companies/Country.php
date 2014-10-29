<?php

namespace Elektra\SeedBundle\Entity\Companies;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Elektra\SeedBundle\Entity\AbstractAuditableEntity;
use Elektra\SeedBundle\Entity\Auditing\Audit;

/**
 * @ORM\Entity(repositoryClass="Elektra\SeedBundle\Repository\Companies\CountryRepository")
 * @ORM\Table(name="countries")
 *
 * @ORM\HasLifecycleCallbacks()
 *
 * Unique:
 *      single fields only:
 *          name
 *          alphaTwo
 *          alphaThree
 *          numericCode
 */
class Country extends AbstractAuditableEntity
{

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer", options={"unsigned" = true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $countryId;

    /**
     * @var Region
     *
     * @ORM\ManyToOne(targetEntity="Elektra\SeedBundle\Entity\Companies\Region", inversedBy="countries",
     *                                                                           fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="regionId", referencedColumnName="regionId", nullable=false)
     */
    protected $region;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=2, unique=true)
     */
    protected $alphaTwo;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=3, unique=true)
     */
    protected $alphaThree;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=3, unique=true)
     */
    protected $numericCode;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50, unique=true)
     */
    protected $name;

    /**
     * @var Collection Audit[]
     *
     * @ORM\ManyToMany(targetEntity = "Elektra\SeedBundle\Entity\Auditing\Audit", fetch="EXTRA_LAZY",
     *                              cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"timestamp" = "DESC"})
     * @ORM\JoinTable(name = "countries_audits",
     *      joinColumns = {@ORM\JoinColumn(name = "countryId", referencedColumnName = "countryId")},
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
    }

    /*************************************************************************
     * Getters / Setters
     *************************************************************************/

    /**
     * @return int
     */
    public function getCountryId()
    {

        return $this->countryId;
    }

    /**
     * @param Region $region
     */
    public function setRegion($region)
    {

        $this->region = $region;
    }

    /**
     * @return Region
     */
    public function getRegion()
    {

        return $this->region;
    }

    /**
     * @param string $alphaTwo
     */
    public function setAlphaTwo($alphaTwo)
    {

        $this->alphaTwo = $alphaTwo;
    }

    /**
     * @return string
     */
    public function getAlphaTwo()
    {

        return $this->alphaTwo;
    }

    /**
     * @param string $alphaThree
     */
    public function setAlphaThree($alphaThree)
    {

        $this->alphaThree = $alphaThree;
    }

    /**
     * @return string
     */
    public function getAlphaThree()
    {

        return $this->alphaThree;
    }

    /**
     * @param string $numeric
     */
    public function setNumericCode($numeric)
    {

        $this->numericCode = $numeric;
    }

    /**
     * @return string
     */
    public function getNumericCode()
    {

        return $this->numericCode;
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

    /*************************************************************************
     * EntityInterface
     *************************************************************************/

    /**
     * {@inheritdoc}
     */
    public function getId()
    {

        return $this->getCountryId();
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