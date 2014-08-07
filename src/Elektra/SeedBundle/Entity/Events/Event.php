<?php

namespace Elektra\SeedBundle\Entity\Events;

use Doctrine\ORM\Mapping as ORM;
use Elektra\SeedBundle\Entity\SeedUnits\SeedUnit;
use Doctrine\Common\Collections\ArrayCollection;
use Elektra\SeedBundle\Entity\Auditing\Audit;
/**
 * Class Event
 *
 * @package Elektra\SeedBundle\Entity\Events
 *
 * @ORM\Entity
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
abstract class Event
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
     * @ORM\ManyToOne(targetEntity="SeedUnit", inversedBy="events", fetch="EXTRA_LAZY")
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
     * @ORM\Column(type="string", length="255")
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
     * @ManyToMany(targetEntity = "Note", fetch="EXTRA_LAZY", cascade={"persist", "remove"})
     * @JoinTable(name = "events_notes",
     *      joinColumns = {@JoinColumn(name = "eventId", referencedColumnName = "eventId")},
     *      inverseJoinColumns = {@JoinColumn(name = "noteId", referencedColumnName = "noteId", unique = true)}
     * )
     */
    protected $notes;

    /**
     * @var Audit
     *
     * @ORM\OneToOne(targetEntity="Audit", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="auditId", referencedColumn="auditId")
     */
    protected $audit;

    public function __construct()
    {
        $this->notes = new ArrayCollection();
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