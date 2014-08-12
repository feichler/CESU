<?php

namespace Elektra\SeedBundle\Entity\Events;

use Doctrine\ORM\Mapping as ORM;
use Elektra\SeedBundle\Entity\AuditableInterface;
use Elektra\SeedBundle\Entity\AnnotableInterface;
use Elektra\SeedBundle\Entity\SeedUnits\SeedUnit;
use Doctrine\Common\Collections\ArrayCollection;
use Elektra\SeedBundle\Entity\Auditing\Audit;
/**
 * Class Event
 *
 * @package Elektra\SeedBundle\Entity\Events
 *
 * @ORM\Entity(repositoryClass="Elektra\SeedBundle\Repositories\Events\EventRepository")
 * @ORM\Table(name="events")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="eventType",type="string")
 * @ORM\DiscriminatorMap({
 *  "shipping" = "ShippingEvent",
 *  "partner" = "PartnerEvent",
 *  "activity" = "ActivityEvent",
 *  "response" = "ResponseEvent",
 * })
 */
abstract class Event implements AuditableInterface, AnnotableInterface
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
     * @var UnitStatus
     *
     * @ORM\ManyToOne(targetEntity="UnitStatus", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="unitStatusId", referencedColumnName="unitStatusId")
     */
    protected $unitStatus;

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
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $text;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity = "Elektra\SeedBundle\Entity\Notes\Note", fetch="EXTRA_LAZY", cascade={"persist", "remove"})
     * @ORM\OrderBy({"timestamp" = "DESC"})
     * @ORM\JoinTable(name = "events_notes",
     *      joinColumns = {@ORM\JoinColumn(name = "eventId", referencedColumnName = "eventId")},
     *      inverseJoinColumns = {@ORM\JoinColumn(name = "noteId", referencedColumnName = "noteId", unique = true)}
     * )
     */
    protected $notes;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity = "Elektra\SeedBundle\Entity\Auditing\Audit", fetch="EXTRA_LAZY", cascade={"persist", "remove"})
     * @ORM\OrderBy({"timestamp" = "DESC"})
     * @ORM\JoinTable(name = "events_audits",
     *      joinColumns = {@ORM\JoinColumn(name = "eventId", referencedColumnName = "eventId")},
     *      inverseJoinColumns = {@ORM\JoinColumn(name = "auditId", referencedColumnName = "auditId", unique = true)}
     * )
     */
    protected $audits;

    public function __construct()
    {
        $this->notes = new ArrayCollection();
        $this->audits = new ArrayCollection();
    }

    /**
     * @return int
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
     * @param UnitStatus $unitStatus
     */
    public function setUnitStatus($unitStatus)
    {
        $this->unitStatus = $unitStatus;
    }

    /**
     * @return UnitStatus
     */
    public function getUnitStatus()
    {
        return $this->unitStatus;
    }

    /**
     * @param string $body
     */
    public function setText($body)
    {
        $this->text = $body;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $subject
     */
    public function setTitle($subject)
    {
        $this->title = $subject;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
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