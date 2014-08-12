<?php

namespace Elektra\SeedBundle\Entity\SeedUnits;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Elektra\SeedBundle\Entity\Auditing\Audit;
use Elektra\SeedBundle\Entity\AuditableInterface;
use Elektra\SeedBundle\Entity\AnnotableInterface;
use Elektra\SeedBundle\Entity\Requests\CompletedRequest;

/**
 * Class SeedUnits
 *
 * @package Elektra\SeedBundle\Entity\SeedUnits
 *
 * @ORM\Entity(repositoryClass="Elektra\SeedBundle\Repositories\SeedUnits\SeedUnitRepository")
 * @ORM\Table(name="seedUnits")
 */
class SeedUnit implements AuditableInterface, AnnotableInterface
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $seedUnitId;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=75, unique=true)
     */
    protected $serialNumber;

    /**
     * @var SeedUnitModel
     *
     * @ORM\ManyToOne(targetEntity="SeedUnitModel", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="seedUnitModelId", referencedColumnName="seedUnitModelId", nullable=false)
     */
    protected $model;

    /**
     * @var PowerCordType
     *
     * @ORM\ManyToOne(targetEntity="PowerCordType", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="powerCordTypeId", referencedColumnName="powerCordTypeId", nullable=false)
     */
    protected $powerCordType;

    /**
     * @var CompletedRequest
     *
     * @ORM\ManyToOne(targetEntity="Elektra\SeedBundle\Entity\Requests\CompletedRequest", inversedBy="seedUnits", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="requestId", referencedColumnName="requestId")
     */
    protected $request;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity = "Elektra\SeedBundle\Entity\Notes\Note", fetch="EXTRA_LAZY", cascade={"persist", "remove"})
     * @ORM\OrderBy({"timestamp" = "DESC"})
     * @ORM\JoinTable(name = "seedUnits_notes",
     *      joinColumns = {@ORM\JoinColumn(name = "seedUnitId", referencedColumnName = "seedUnitId")},
     *      inverseJoinColumns = {@ORM\JoinColumn(name = "noteId", referencedColumnName = "noteId", unique = true)}
     * )
     */
    protected $notes;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity = "Elektra\SeedBundle\Entity\Auditing\Audit", fetch="EXTRA_LAZY", cascade={"persist", "remove"})
     * @ORM\OrderBy({"timestamp" = "DESC"})
     * @ORM\JoinTable(name = "seedUnits_audits",
     *      joinColumns = {@ORM\JoinColumn(name = "seedUnitId", referencedColumnName = "seedUnitId")},
     *      inverseJoinColumns = {@ORM\JoinColumn(name = "auditId", referencedColumnName = "auditId", unique = true)}
     * )
     */
    protected $audits;

    function __construct()
    {
        $this->notes = new ArrayCollection();
        $this->audits = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->seedUnitId;
    }

    /**
     * @return int
     */
    public function getSeedUnitId()
    {
        return $this->seedUnitId;
    }

    /**
     * @param SeedUnitModel $model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

    /**
     * @return SeedUnitModel
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param PowerCordType $powerCordType
     */
    public function setPowerCordType($powerCordType)
    {
        $this->powerCordType = $powerCordType;
    }

    /**
     * @return PowerCordType
     */
    public function getPowerCordType()
    {
        return $this->powerCordType;
    }

    /**
     * @param \Elektra\SeedBundle\Entity\Requests\CompletedRequest $request
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }

    /**
     * @return \Elektra\SeedBundle\Entity\Requests\CompletedRequest
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param string $serialNumber
     */
    public function setSerialNumber($serialNumber)
    {
        $this->serialNumber = $serialNumber;
    }

    /**
     * @return string
     */
    public function getSerialNumber()
    {
        return $this->serialNumber;
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
        return $this->getAudits()->slice(0, 1);
    }

    /**
     * @return Audit
     */
    public function getLastModifiedAudit()
    {
        $audits = $this->getAudits();
        return $audits->slice($audits->count()-1, 1);
    }
}