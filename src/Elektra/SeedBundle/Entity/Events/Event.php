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
use Elektra\SeedBundle\Entity\AuditableInterface;
use Elektra\SeedBundle\Entity\AnnotableInterface;
use Elektra\SeedBundle\Entity\CRUDEntityInterface;
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
 *  "status" = "StatusEvent",
 *  "sales" = "SalesEvent"
 * })
 */
abstract class Event implements AuditableInterface, AnnotableInterface, CrudInterface
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
}