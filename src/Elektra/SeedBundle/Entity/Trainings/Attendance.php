<?php

namespace Elektra\SeedBundle\Entity\Trainings;

use Doctrine\ORM\Mapping as ORM;
use Elektra\SeedBundle\Entity\Companies\Person;

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
     * @var Person
     *
     * @ORM\ManyToOne(targetEntity="Person", inversedBy="attendances", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="personId",referencedColumnName="personId")
     */
    protected $person;

    /**
     * @var Training
     *
     * @ORM\ManyToOne(targetEntity="Training", inversedBy="attendances", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="trainingId",referencedColumnName="trainingId")
     */
    protected $training;

    public function __construct()
    {
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
     * @param Person $person
     */
    public function setPerson($person)
    {
        $this->person = $person;
    }

    /**
     * @return Person
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
}