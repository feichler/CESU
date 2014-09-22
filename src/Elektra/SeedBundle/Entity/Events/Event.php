<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Entity\Events;

use Doctrine\ORM\Mapping as ORM;
use Elektra\CrudBundle\Entity\EntityInterface as CrudInterface;
use Elektra\SeedBundle\Auditing\Helper;
use Elektra\SeedBundle\Entity\AuditableInterface;
use Elektra\SeedBundle\Entity\AnnotableInterface;
use Elektra\SeedBundle\Entity\Companies\Location;
use Elektra\SeedBundle\Entity\SeedUnits\SeedUnit;
use Doctrine\Common\Collections\ArrayCollection;
use Elektra\SeedBundle\Entity\Auditing\Audit;

/**
 * Class Event
 *
 * @package Elektra\SeedBundle\Entity\Events
 *
 * @version 0.1-dev
 *
 * @ORM\Entity(repositoryClass="Elektra\SeedBundle\Repository\Events\EventRepository")
 * @ORM\Table(name="events")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type",type="string")
 * @ORM\DiscriminatorMap({
 *  "event"    = "Event",
 *  "shipping" = "ShippingEvent",
 *  "activity" = "ActivityEvent",
 * })
 */
class Event implements AuditableInterface, AnnotableInterface, CrudInterface
{

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $eventId;

    /**
     * @var \Elektra\SeedBundle\Entity\SeedUnits\SeedUnit
     *
     * @ORM\ManyToOne(targetEntity="Elektra\SeedBundle\Entity\SeedUnits\SeedUnit", inversedBy="events", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="seedUnitId", referencedColumnName="seedUnitId", nullable=false)
     */
    protected $seedUnit;

    /**
     * @var EventType
     *
     * @ORM\ManyToOne(targetEntity="EventType", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="eventTypeId", referencedColumnName="eventTypeId", nullable=false)
     */
    protected $eventType;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $timestamp;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $text;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $comment;

    /**
     * @var UnitStatus
     *
     * @ORM\ManyToOne(targetEntity="Elektra\SeedBundle\Entity\Events\UnitStatus", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="unitStatusId", referencedColumnName="unitStatusId")
     */
    protected $unitStatus;

    /**
     * @var UnitSalesStatus
     *
     * @ORM\ManyToOne(targetEntity="Elektra\SeedBundle\Entity\Events\UnitSalesStatus", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="unitSalesStatusId", referencedColumnName="unitSalesStatusId")
     */
    protected $salesStatus;

    /**
     * @var Location
     *
     * @ORM\ManyToOne(targetEntity="Elektra\SeedBundle\Entity\Companies\Location", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="locationId", referencedColumnName="locationId")
     */
    protected $location;

    /**
     * @var UnitUsage
     *
     * @ORM\ManyToOne(targetEntity="Elektra\SeedBundle\Entity\Events\UnitUsage", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="unitUsageId", referencedColumnName="unitUsageId")
     */
    protected $usage;


    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity = "Elektra\SeedBundle\Entity\Notes\Note", fetch="EXTRA_LAZY", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"timestamp" = "DESC"})
     * @ORM\JoinTable(name = "events_notes",
     *      joinColumns = {@ORM\JoinColumn(name = "eventId", referencedColumnName = "eventId", onDelete="CASCADE")},
     *      inverseJoinColumns = {@ORM\JoinColumn(name = "noteId", referencedColumnName = "noteId", unique = true, onDelete="CASCADE")}
     * )
     */
    protected $notes;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity = "Elektra\SeedBundle\Entity\Auditing\Audit", fetch="EXTRA_LAZY", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"timestamp" = "DESC"})
     * @ORM\JoinTable(name = "events_audits",
     *      joinColumns = {@ORM\JoinColumn(name = "eventId", referencedColumnName = "eventId")},
     *      inverseJoinColumns = {@ORM\JoinColumn(name = "auditId", referencedColumnName = "auditId", unique = true, onDelete="CASCADE")}
     * )
     */
    protected $audits;

    /**
     *
     */
    public function __construct()
    {

        $this->notes  = new ArrayCollection();
        $this->audits = new ArrayCollection();
        $this->timestamp = time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {

        return $this->eventId;
    }

    /**
     * @return int
     */
    public function getEventId()
    {

        return $this->eventId;
    }

    /**
     * @param EventType $eventType
     */
    public function setEventType($eventType)
    {

        $this->eventType = $eventType;
    }

    /**
     * @return EventType
     */
    public function getEventType()
    {

        return $this->eventType;
    }

    /**
     * @param SeedUnit $seedUnit
     */
    public function setSeedUnit($seedUnit)
    {

        $this->seedUnit = $seedUnit;
    }

    /**
     * @return SeedUnit
     */
    public function getSeedUnit()
    {

        return $this->seedUnit;
    }

    /**
     * @param string $body
     */
    public function setComment($body)
    {

        $this->comment = $body;
    }

    /**
     * @return string
     */
    public function getComment()
    {

        return $this->comment;
    }

    /**
     * @param string $subject
     */
    public function setText($subject)
    {

        $this->text = $subject;
    }

    /**
     * @return string
     */
    public function getText()
    {

        return $this->text;
    }

    /**
     * @param int $timestamp
     */
    public function setTimestamp($timestamp)
    {

        $this->timestamp = $timestamp;
    }

    /**
     * @return int
     */
    public function getTimestamp()
    {

        return $this->timestamp;
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
     * @return string
     */
    public function getTitle()
    {
        return $this->getText();
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
     * @param \Elektra\SeedBundle\Entity\Events\UnitStatus $unitStatus
     */
    public function setUnitStatus($unitStatus)
    {
        $this->unitStatus = $unitStatus;
    }

    /**
     * @return \Elektra\SeedBundle\Entity\Events\UnitStatus
     */
    public function getUnitStatus()
    {
        return $this->unitStatus;
    }

    /**
     * @param \Elektra\SeedBundle\Entity\Events\UnitUsage $usage
     */
    public function setUsage($usage)
    {
        $this->usage = $usage;
    }

    /**
     * @return \Elektra\SeedBundle\Entity\Events\UnitUsage
     */
    public function getUsage()
    {
        return $this->usage;
    }

    public function createClone()
    {

        $event = clone $this;
        // WORKAROUND: need to explicitly clear audits because list is not cleared on clone
        $event->setAudits(new ArrayCollection());
        $event->setNotes(new ArrayCollection());

        return $event;
    }
}