<?php

namespace Elektra\SeedBundle\Entity\Trainings;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class Training
 *
 * @package Elektra\SeedBundle\Entity\Trainings
 *
 * @ORM\Entity
 * @ORM\Table("trainings")
 */
class Training
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
     * @ORM\Column(type="string", length=50)
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

    public function __construct()
    {
        $this->attendances = new ArrayCollection();
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
}