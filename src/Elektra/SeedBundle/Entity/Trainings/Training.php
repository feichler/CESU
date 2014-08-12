<?php

namespace Elektra\SeedBundle\Entity\Trainings;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Elektra\SeedBundle\Entity\Auditing\Audit;
use Elektra\SeedBundle\Entity\AuditableInterface;
use Elektra\SeedBundle\Entity\AnnotableInterface;

/**
 * Class Training
 *
 * @package Elektra\SeedBundle\Entity\Trainings
 *
 * @ORM\Entity(repositoryClass="Elektra\SeedBundle\Repositories\Trainings\TrainingRepository")
 * @ORM\Table("trainings")
 */
class Training implements AuditableInterface, AnnotableInterface
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $trainingId;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    protected $location;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $startedAt;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $endedAt;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Attendance", mappedBy="training", fetch="EXTRA_LAZY")
     */
    protected $attendances;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Registration", mappedBy="training", fetch="EXTRA_LAZY")
     */
    protected $registrations;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity = "Elektra\SeedBundle\Entity\Notes\Note", fetch="EXTRA_LAZY", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"timestamp" = "DESC"})
     * @ORM\JoinTable(name = "trainings_notes",
     *      joinColumns = {@ORM\JoinColumn(name = "trainingId", referencedColumnName = "trainingId", onDelete="CASCADE")},
     *      inverseJoinColumns = {@ORM\JoinColumn(name = "noteId", referencedColumnName = "noteId", unique = true, onDelete="CASCADE")}
     * )
     */
    protected $notes;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity = "Elektra\SeedBundle\Entity\Auditing\Audit", fetch="EXTRA_LAZY", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"timestamp" = "DESC"})
     * @ORM\JoinTable(name = "trainings_audits",
     *      joinColumns = {@ORM\JoinColumn(name = "trainingId", referencedColumnName = "trainingId")},
     *      inverseJoinColumns = {@ORM\JoinColumn(name = "auditId", referencedColumnName = "auditId", unique = true, onDelete="CASCADE")}
     * )
     */
    protected $audits;

    public function __construct()
    {
        $this->attendances = new ArrayCollection();
        $this->registrations = new ArrayCollection();
        $this->notes = new ArrayCollection();
        $this->audits = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->trainingId;
    }

    /**
     * @return int
     */
    public function getTrainingId()
    {
        return $this->trainingId;
    }

    /**
     * @param ArrayCollection $attendances
     */
    public function setAttendances($attendances)
    {
        $this->attendances = $attendances;
    }

    /**
     * @return ArrayCollection
     */
    public function getAttendances()
    {
        return $this->attendances;
    }

    /**
     * @param ArrayCollection $registrations
     */
    public function setRegistrations($registrations)
    {
        $this->registrations = $registrations;
    }

    /**
     * @return ArrayCollection
     */
    public function getRegistrations()
    {
        return $this->registrations;
    }

    /**
     * @param int $startedAt
     */
    public function setStartedAt($startedAt)
    {
        $this->startedAt = $startedAt;
    }

    /**
     * @return int
     */
    public function getStartedAt()
    {
        return $this->startedAt;
    }

    /**
     * @param string $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
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
     * @param int $endedAt
     */
    public function setEndedAt($endedAt)
    {
        $this->endedAt = $endedAt;
    }

    /**
     * @return int
     */
    public function getEndedAt()
    {
        return $this->endedAt;
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