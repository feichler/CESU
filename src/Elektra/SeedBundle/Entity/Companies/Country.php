<?php

namespace Elektra\SeedBundle\Entity\Companies;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Elektra\SeedBundle\Entity\Auditing\Audit;
use Elektra\SeedBundle\Entity\AuditableInterface;

/**
 * Class Country
 *
 * @package Elektra\SeedBundle\Entity\Companies
 *
 * @ORM\Entity
 * @ORM\Table(name="countries")
 */
class Country implements AuditableInterface
{

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $countryId;

    /**
     * @var Region
     *
     * @ORM\ManyToOne(targetEntity="Region", inversedBy="countries", fetch="EXTRA_LAZY")
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
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity = "Elektra\SeedBundle\Entity\Auditing\Audit", fetch="EXTRA_LAZY", cascade={"persist", "remove"})
     * @ORM\JoinTable(name = "countries_audits",
     *      joinColumns = {@ORM\JoinColumn(name = "countryId", referencedColumnName = "countryId")},
     *      inverseJoinColumns = {@ORM\JoinColumn(name = "auditId", referencedColumnName = "auditId", unique = true)}
     */
    protected $audits;

    public function __construct()
    {
        $this->audits = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {

        return $this->countryId;
    }

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

    /**
     * @param ArrayCollection
     */
    public function setAudits($audits)
    {
        $this->audits = $audits;
    }

    /**
     * @return ArrayCollection
     */
    public function getAudits()
    {
        return $this->audits;
    }
}