<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Entity\SeedUnits;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Elektra\CrudBundle\Entity\EntityInterface as CrudInterface;
use Elektra\SeedBundle\Auditing\Helper;
use Elektra\SeedBundle\Entity\Auditing\Audit;
use Elektra\SeedBundle\Entity\AuditableInterface;
use Elektra\SeedBundle\Entity\AnnotableInterface;
use Elektra\SeedBundle\Entity\Companies\Location;
use Elektra\SeedBundle\Entity\Events\UnitSalesStatus;
use Elektra\SeedBundle\Entity\Events\UnitStatus;
use Elektra\SeedBundle\Entity\Events\UnitUsage;
use Elektra\SeedBundle\Entity\Requests\Request;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class SeedUnits
 *
 * @package Elektra\SeedBundle\Entity\SeedUnits
 *
 * @version 0.1-dev
 *
 * @ORM\Entity(repositoryClass="Elektra\SeedBundle\Repository\SeedUnits\SeedUnitRepository")
 * @ORM\Table(name="seedUnits")
 * @UniqueEntity(fields={ "serialNumber" }, message="")
 */
class SeedUnit implements AuditableInterface, AnnotableInterface, CrudInterface
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
     * @var Model
     *
     * @ORM\ManyToOne(targetEntity="Model", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="modelId", referencedColumnName="modelId", nullable=false)
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
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Elektra\SeedBundle\Entity\Events\Event", mappedBy="seedUnit", fetch="EXTRA_LAZY", cascade={"persist", "remove"})
     * @ORM\OrderBy({"timestamp" = "DESC"})
     * )
     */
    protected $events;

    /**
     * @var Request
     *
     * @ORM\ManyToOne(targetEntity="Elektra\SeedBundle\Entity\Requests\Request", inversedBy="seedUnits", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="requestId", referencedColumnName="requestId")
     */
    protected $request;

    /**
     * @var UnitStatus
     *
     * @ORM\ManyToOne(targetEntity="Elektra\SeedBundle\Entity\Events\UnitStatus", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="shippingStatusId", referencedColumnName="shippingStatusId", nullable=false)
     */
    protected $shippingStatus;

    /**
     * @var UnitUsage
     *
     * @ORM\ManyToOne(targetEntity="Elektra\SeedBundle\Entity\Events\UnitUsage", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="usageStatusId", referencedColumnName="usageStatusId")
     */
    protected $unitUsage;

    /**
     * @var UnitSalesStatus
     *
     * @ORM\ManyToOne(targetEntity="Elektra\SeedBundle\Entity\Events\UnitSalesStatus", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="salesStatusId", referencedColumnName="salesStatusId")
     */
    protected $salesStatus;

    /**
     * @var Location
     *
     * @ORM\ManyToOne(targetEntity="Elektra\SeedBundle\Entity\Companies\Location", inversedBy="seedUnits", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="locationId", referencedColumnName="locationId", nullable=false)
     */
    protected $location;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity = "Elektra\SeedBundle\Entity\Notes\Note", fetch="EXTRA_LAZY", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"timestamp" = "DESC"})
     * @ORM\JoinTable(name = "seedUnits_notes",
     *      joinColumns = {@ORM\JoinColumn(name = "seedUnitId", referencedColumnName = "seedUnitId", onDelete="CASCADE")},
     *      inverseJoinColumns = {@ORM\JoinColumn(name = "noteId", referencedColumnName = "noteId", unique = true, onDelete="CASCADE")}
     * )
     */
    protected $notes;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity = "Elektra\SeedBundle\Entity\Auditing\Audit", fetch="EXTRA_LAZY", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"timestamp" = "DESC"})
     * @ORM\JoinTable(name = "seedUnits_audits",
     *      joinColumns = {@ORM\JoinColumn(name = "seedUnitId", referencedColumnName = "seedUnitId")},
     *      inverseJoinColumns = {@ORM\JoinColumn(name = "auditId", referencedColumnName = "auditId", unique = true, onDelete="CASCADE")}
     * )
     */
    protected $audits;

    /**
     *
     */
    function __construct()
    {

        $this->notes  = new ArrayCollection();
        $this->audits = new ArrayCollection();
        $this->events = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
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
     * @param Model $model
     */
    public function setModel($model)
    {

        $this->model = $model;
    }

    /**
     * @return Model
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
     * @param \Doctrine\Common\Collections\ArrayCollection $events
     */
    public function setEvents($events)
    {

        $this->events = $events;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getEvents()
    {

        return $this->events;
    }

    /**
     * @param Request $request
     */
    public function setRequest($request)
    {

        $this->request = $request;
    }

    /**
     * @return Request
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

        return $this->getSerialNumber();
    }

    /**
     * @param \Elektra\SeedBundle\Entity\Companies\Location $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @return \Elektra\SeedBundle\Entity\Companies\Location
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param \Elektra\SeedBundle\Entity\Events\UnitSalesStatus $salesStatus
     */
    public function setSalesStatus($salesStatus)
    {
        $this->salesStatus = $salesStatus;
    }

    /**
     * @return \Elektra\SeedBundle\Entity\Events\UnitSalesStatus
     */
    public function getSalesStatus()
    {
        return $this->salesStatus;
    }

    /**
     * @param \Elektra\SeedBundle\Entity\Events\UnitStatus $shippingStatus
     */
    public function setShippingStatus($shippingStatus)
    {
        $this->shippingStatus = $shippingStatus;
    }

    /**
     * @return \Elektra\SeedBundle\Entity\Events\UnitStatus
     */
    public function getShippingStatus()
    {
        return $this->shippingStatus;
    }

    /**
     * @param \Elektra\SeedBundle\Entity\Events\UnitUsage $unitUsage
     */
    public function setUnitUsage($unitUsage)
    {
        $this->unitUsage = $unitUsage;
    }

    /**
     * @return \Elektra\SeedBundle\Entity\Events\UnitUsage
     */
    public function getUnitUsage()
    {
        return $this->unitUsage;
    }
}