<?php

namespace Elektra\SeedBundle\Entity\Events;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Elektra\SeedBundle\Entity\AbstractAuditableAnnotableEntity;
use Elektra\SeedBundle\Entity\Auditing\Audit;
use Elektra\SeedBundle\Entity\Companies\AbstractLocation;
use Elektra\SeedBundle\Entity\SeedUnits\SeedUnit;
use Elektra\SeedBundle\Entity\SeedUnits\StatusSales;
use Elektra\SeedBundle\Entity\SeedUnits\StatusShipping;
use Elektra\SeedBundle\Entity\SeedUnits\StatusUsage;

/**
 *
 * @ORM\Entity(repositoryClass="Elektra\SeedBundle\Repository\Events\EventRepository")
 * @ORM\Table(name="events")
 *
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type",type="string")
 *
 * @ORM\HasLifecycleCallbacks()
 *
 * Unique: nothing?
 */
class Event extends AbstractAuditableAnnotableEntity
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
     * @var EventType
     *
     * @ORM\ManyToOne(targetEntity="Elektra\SeedBundle\Entity\Events\EventType", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="eventTypeId", referencedColumnName="eventTypeId", nullable=false)
     */
    protected $eventType;

    /**
     * @var SeedUnit
     *
     * @ORM\ManyToOne(targetEntity="Elektra\SeedBundle\Entity\SeedUnits\SeedUnit", inversedBy="events",
     *                                                                             fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="seedUnitId", referencedColumnName="seedUnitId", nullable=false)
     */
    protected $seedUnit;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $timestamp;

    /**
     * // TODO rename into title?
     *
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
     * @var StatusShipping
     *
     * @ORM\ManyToOne(targetEntity="Elektra\SeedBundle\Entity\SeedUnits\StatusShipping", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="statusShippingId", referencedColumnName="statusId")
     */
    protected $statusShipping;

    /**
     * @var StatusSales
     *
     * @ORM\ManyToOne(targetEntity="Elektra\SeedBundle\Entity\SeedUnits\StatusSales", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="statusSalesId", referencedColumnName="statusId")
     */
    protected $statusSales;

    /**
     * @var StatusUsage
     *
     * @ORM\ManyToOne(targetEntity="Elektra\SeedBundle\Entity\SeedUnits\StatusUsage", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="statusUsageId", referencedColumnName="statusId")
     */
    protected $statusUsage;

    /**
     * @var AbstractLocation
     *
     * @ORM\ManyToOne(targetEntity="Elektra\SeedBundle\Entity\Companies\AbstractLocation", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="locationId", referencedColumnName="locationId")
     */
    protected $location;

    /**
     * @var Collection Note[]
     *
     * @ORM\ManyToMany(targetEntity = "Elektra\SeedBundle\Entity\Notes\Note", fetch="EXTRA_LAZY", cascade={"persist",
     *                              "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"timestamp" = "DESC"})
     * @ORM\JoinTable(name = "events_notes",
     *      joinColumns = {@ORM\JoinColumn(name = "eventId", referencedColumnName = "eventId", onDelete="CASCADE")},
     *      inverseJoinColumns = {@ORM\JoinColumn(name = "noteId", referencedColumnName = "noteId", unique = true,
     *      onDelete="CASCADE")}
     * )
     */
    protected $notes;

    /**
     * @var Collection Audit[]
     *
     * @ORM\ManyToMany(targetEntity = "Elektra\SeedBundle\Entity\Auditing\Audit", fetch="EXTRA_LAZY",
     *                              cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"timestamp" = "DESC"})
     * @ORM\JoinTable(name = "events_audits",
     *      joinColumns = {@ORM\JoinColumn(name = "eventId", referencedColumnName = "eventId")},
     *      inverseJoinColumns = {@ORM\JoinColumn(name = "auditId", referencedColumnName = "auditId", unique = true,
     *      onDelete="CASCADE")}
     * )
     */
    protected $audits;

    /**
     * Constructor
     */
    public function __construct()
    {

        parent::__construct();
        $this->timestamp = time();
    }

    /*************************************************************************
     * Getters / Setters
     *************************************************************************/

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
     * @param StatusShipping $statusShipping
     */
    public function setStatusShipping($statusShipping)
    {

        $this->statusShipping = $statusShipping;
    }

    /**
     * @return StatusShipping
     */
    public function getStatusShipping()
    {

        return $this->statusShipping;
    }

    /**
     * @param StatusSales $statusSales
     */
    public function setStatusSales($statusSales)
    {

        $this->statusSales = $statusSales;
    }

    /**
     * @return StatusSales
     */
    public function getStatusSales()
    {

        return $this->statusSales;
    }

    /**
     * @param StatusUsage $usage
     */
    public function setStatusUsage($usage)
    {

        $this->statusUsage = $usage;
    }

    /**
     * @return StatusUsage
     */
    public function getStatusUsage()
    {

        return $this->statusUsage;
    }

    /**
     * @param AbstractLocation $location
     */
    public function setLocation($location)
    {

        $this->location = $location;
    }

    /**
     * @return AbstractLocation
     */
    public function getLocation()
    {

        return $this->location;
    }

    /*************************************************************************
     * Other methods
     *************************************************************************/

    public function createClone()
    {

        $event = clone $this;

        // WORKAROUND: need to explicitly clear audits because list is not cleared on clone
        $event->setAudits(new ArrayCollection());
        $event->setNotes(new ArrayCollection());

        return $event;
    }

    /*************************************************************************
     * EntityInterface
     *************************************************************************/

    /**
     * {@inheritdoc}
     */
    public function getId()
    {

        return $this->getEventId();
    }

    /**
     * {@inheritdoc}
     */
    public function getDisplayName()
    {

        return $this->getText();
    }

    /*************************************************************************
     * Lifecycle callbacks
     *************************************************************************/

    // none
}