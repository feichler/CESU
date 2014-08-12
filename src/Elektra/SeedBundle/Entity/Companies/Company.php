<?php

namespace Elektra\SeedBundle\Entity\Companies;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Elektra\SeedBundle\Entity\Auditing\Audit;

/**
 * Class Company
 *
 * @package Elektra\SeedBundle\Entity\Companies
 *
 * @ORM\Entity(repositoryClass="Elektra\SeedBundle\Repositories\Companies\CompanyRepository")
 * @ORM\Table(name="companies")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="companyType",type="string")
 * @ORM\DiscriminatorMap({
 *  "partner" = "Partner",
 *  "customer" = "Customer"
 * })
 */
abstract class Company
{

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $companyId;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, unique=true)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50, unique=true)
     */
    protected $shortName;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Location", mappedBy="company", fetch="EXTRA_LAZY", cascade={"remove"})
     */
    protected $locations;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity = "Elektra\SeedBundle\Entity\Notes\Note", fetch="EXTRA_LAZY", cascade={"persist", "remove"})
     * @ORM\OrderBy({"timestamp" = "DESC"})
     * @ORM\JoinTable(name = "companies_notes",
     *      joinColumns = {@ORM\JoinColumn(name = "companyId", referencedColumnName = "companyId")},
     *      inverseJoinColumns = {@ORM\JoinColumn(name = "noteId", referencedColumnName = "noteId", unique = true)}
     * )
     */
    protected $notes;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity = "Elektra\SeedBundle\Entity\Auditing\Audit", fetch="EXTRA_LAZY", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"timestamp" = "DESC"})
     * @ORM\JoinTable(name = "companies_audits",
     *      joinColumns = {@ORM\JoinColumn(name = "companyId", referencedColumnName = "companyId")},
     *      inverseJoinColumns = {@ORM\JoinColumn(name = "auditId", referencedColumnName = "auditId", unique = true, onDelete="CASCADE")}
     * )
     */
    protected $audits;

    public function __construct()
    {
        $this->locations = new ArrayCollection();
        $this->notes = new ArrayCollection();
        $this->audits = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->companyId;
    }

    /**
     * @return int
     */
    public function getCompanyId()
    {
        return $this->companyId;
    }

    /**
     * @param ArrayCollection $locations
     */
    public function setLocations($locations)
    {
        $this->locations = $locations;
    }

    /**
     * @return ArrayCollection
     */
    public function getLocations()
    {
        return $this->locations;
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
     * @param string $shortName
     */
    public function setShortName($shortName)
    {
        $this->shortName = $shortName;
    }

    /**
     * @return string
     */
    public function getShortName()
    {
        return $this->shortName;
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
    /**
     * @return Audit
     */
    public function getCreationAudit()
    {
        return $this->getAudits()->slice(0, 1)[0];
    }

    /**
     * @return Audit
     */
    public function getLastModifiedAudit()
    {
        $audits = $this->getAudits();
        return $audits->count() > 1 ? $audits->slice($audits->count()-1, 1)[0] : null;
    }
}