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
     * @ORM\ManyToMany(targetEntity = "Elektra\SeedBundle\Entity\Notes\Note", fetch="EXTRA_LAZY", cascade={"persist", "remove"})
     * @ORM\JoinTable(name = "trainings_notes",
     *      joinColumns = {@ORM\JoinColumn(name = "trainingId", referencedColumnName = "trainingId")},
     *      inverseJoinColumns = {@ORM\JoinColumn(name = "noteId", referencedColumnName = "noteId", unique = true)}
     * )
     */
    protected $notes;

    /**
     * @var Audit
     *
     * @ORM\OneToOne(targetEntity="Elektra\SeedBundle\Entity\Auditing\Audit", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="auditId", referencedColumnName="auditId")
     */
    protected $audit;

    public function __construct()
    {
        $this->attendances = new ArrayCollection();
        $this->registrations = new ArrayCollection();
        $this->notes = new ArrayCollection();
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