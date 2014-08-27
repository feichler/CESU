<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Entity\Companies;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Elektra\CrudBundle\Entity\EntityInterface as CrudInterface;
use Elektra\SeedBundle\Entity\AnnotableInterface;
use Elektra\SeedBundle\Entity\AuditableInterface;
use Elektra\SeedBundle\Entity\Auditing\Audit;

/**
 * Class Company
 *
 * @package Elektra\SeedBundle\Entity\Companies
 *
 * @version 0.1-dev
 *
 * @ORM\Entity(repositoryClass="Elektra\SeedBundle\Repository\Companies\CompanyRepository")
 * @ORM\Table(name="companies")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="companyType",type="string")
 * @ORM\DiscriminatorMap({
 *  "partner" = "Partner",
 *  "salesTeam" = "SalesTeam",
 *  "customer" = "Customer"
 * })
 */
abstract class Company implements AuditableInterface, AnnotableInterface, CrudInterface
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50)
     */
    protected $shortName;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="CompanyLocation", mappedBy="company", fetch="EXTRA_LAZY", cascade={"remove"})
     */
    protected $locations;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity = "Elektra\SeedBundle\Entity\Notes\Note", fetch="EXTRA_LAZY", cascade={"persist", "remove"}, orphanRemoval=true)
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

    /**
     *
     */
    public function __construct()
    {

        $this->locations = new ArrayCollection();
        $this->notes     = new ArrayCollection();
        $this->audits    = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function setNotes($notes)
    {

        $this->notes = $notes;
    }

    /**
     * {@inheritdoc}
     */
    public function getNotes()
    {

        return $this->notes;
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

        $audits = $this->getAudits()->slice(0, 1);

        return $audits[0];
    }

    /**
     * {@inheritdoc}
     */
    public function getLastModifiedAudit()
    {

        $audits = $this->getAudits();
        if ($audits->count() > 1) {
            $audits = $audits->slice($audits->count() - 1, 1);

            return $audits[0];
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getTitle()
    {

        return $this->getShortName();
    }

    /**
     * @return CompanyLocation
     */
    public function getPrimaryLocation()
    {

        $primaryLocation = $this->getLocations()->matching(
            Criteria::create()->where(Criteria::expr()->eq("isPrimary", true))->setMaxResults(1)
        )->first();

        return $primaryLocation;
    }
}