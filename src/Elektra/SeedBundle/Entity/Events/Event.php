<?php

namespace Elektra\SeedBundle\Entity\Events;

use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\JoinColumn(name="seedUnitId", referencedColumnName="seedUnitId")
     */
    protected $seedUnit;

    /**
     * @var EventType
     *
     * @ORM\ManyToOne(targetEntity="EventType", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="eventTypeId", referencedColumnName="eventTypeId")
     */
    protected $eventType;

    /**
     * @var UnitStatus
     *
     * @ORM\ManyToOne(targetEntity="UnitStatus", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="unitStatusId", referencedColumnName="UnitStatusId")
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
    protected $subject;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $body;

    public function __construct()
    {
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
     * @param \Elektra\SeedBundle\Entity\Events\EventType $eventType
     */
    public function setEventType($eventType)
    {
        $this->eventType = $eventType;
    }

    /**
     * @return \Elektra\SeedBundle\Entity\Events\EventType
     */
    public function getEventType()
    {
        return $this->eventType;
    }

    /**
     * @param \Elektra\SeedBundle\Entity\SeedUnits\SeedUnit $seedUnit
     */
    public function setSeedUnit($seedUnit)
    {
        $this->seedUnit = $seedUnit;
    }

    /**
     * @return \Elektra\SeedBundle\Entity\SeedUnits\SeedUnit
     */
    public function getSeedUnit()
    {
        return $this->seedUnit;
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
     * @param string $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
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
}