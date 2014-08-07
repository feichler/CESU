<?php

namespace Elektra\SeedBundle\Entity\Trainings;

use Doctrine\ORM\Mapping as ORM;
use Elektra\SeedBundle\Entity\Companies\CompanyPerson;
use Doctrine\Common\Collections\ArrayCollection;
use Elektra\SeedBundle\Entity\Auditing\Audit;

/**
 * Class Attendance
 *
 * @package Elektra\SeedBundle\Entity\Trainings
 *
 * @ORM\Entity
 * @ORM\Table(name="attendances")
 */
class Attendance
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $attendanceId;

    /**
     * @var CompanyPerson
     *
     * @ORM\ManyToOne(targetEntity="Elektra\SeedBundle\Entity\Companies\Person", inversedBy="attendances", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="personId", referencedColumnName="personId", nullable=false)
     */
    protected $person;

    /**
     * @var Training
     *
     * @ORM\ManyToOne(targetEntity="Training", inversedBy="attendances", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="trainingId", referencedColumnName="trainingId", nullable=false)
     */
    protected $training;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity = "Elektra\SeedBundle\Entity\Notes\Note", cascade={"persist", "remove"})
     * @ORM\JoinTable(name = "attendances_notes",
     *      joinColumns = {@ORM\JoinColumn(name = "attendanceId", referencedColumnName = "attendanceId")},
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
        $this->notes = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->attendanceId;
    }

    /**
     * @return int
     */
    public function getAttendanceId()
    {
        return $this->attendanceId;
    }

    /**
     * @param CompanyPerson $person
     */
    public function setPerson($person)
    {
        $this->person = $person;
    }

    /**
     * @return CompanyPerson
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * @param Training $training
     */
    public function setTraining($training)
    {
        $this->training = $training;
    }

    /**
     * @return Training
     */
    public function getTraining()
    {
        return $this->training;
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