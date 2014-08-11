<?php

namespace Elektra\SeedBundle\Entity\Companies;

use Doctrine\ORM\Mapping as ORM;
use Elektra\SeedBundle\Entity\Auditing\Audit;
use Elektra\SeedBundle\Entity\IAuditContainer;

/**
 * Class Country
 *
 * @package Elektra\SeedBundle\Entity\Companies
 *
 * @ORM\Entity
 * @ORM\Table(name="countries")
 */
class Country implements IAuditContainer
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
     * @var Audit
     *
     * @ORM\OneToOne(targetEntity="Elektra\SeedBundle\Entity\Auditing\Audit", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="auditId", referencedColumnName="auditId")
     */
    protected $audit;

    public function __construct()
    {
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