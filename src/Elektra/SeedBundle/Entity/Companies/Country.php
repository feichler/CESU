<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Entity\Companies;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Elektra\CrudBundle\Entity\EntityInterface as CrudInterface;
use Elektra\SeedBundle\Auditing\Helper;
use Elektra\SeedBundle\Entity\Auditing\Audit;
use Elektra\SeedBundle\Entity\AuditableInterface;

/**
 * Class Country
 *
 * @package Elektra\SeedBundle\Entity\Companies
 *
 * @version 0.1-dev
 *
 * @ORM\Entity(repositoryClass="Elektra\SeedBundle\Repository\Companies\CountryRepository")
 * @ORM\Table(name="countries")
 */
class Country implements AuditableInterface, CrudInterface
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
     * @ORM\ManyToMany(targetEntity = "Elektra\SeedBundle\Entity\Auditing\Audit", fetch="EXTRA_LAZY", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"timestamp" = "DESC"})
     * @ORM\JoinTable(name = "countries_audits",
     *      joinColumns = {@ORM\JoinColumn(name = "countryId", referencedColumnName = "countryId")},
     *      inverseJoinColumns = {@ORM\JoinColumn(name = "auditId", referencedColumnName = "auditId", unique = true, onDelete="CASCADE")}
     * )
     */
    protected $audits;

    /**
     *
     */
    public function __construct()
    {

        $this->audits = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function setAudits($audits)
    {

        $this->audits = $audits;
    }

    /**
     * {@inheritdoc}
     */
    public function getAudits()
    {

        return $this->audits;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreationAudit()
    {
        return Helper::getFirstAudit($this->getAudits());

    }

    /**
     * {@inheritdoc}
     */
    public function getLastModifiedAudit()
    {
        return Helper::getLastAudit($this->getAudits());

    }

    /**
     * {@inheritdoc}
     */
    public function getTitle()
    {

        return $this->getName();
    }
}