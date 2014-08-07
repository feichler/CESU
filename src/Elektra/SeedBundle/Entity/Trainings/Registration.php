<?php

namespace Elektra\SeedBundle\Entity\Trainings;

use Doctrine\ORM\Mapping as ORM;
use Elektra\SeedBundle\Entity\Companies\CompanyPerson;

/**
 * Class Registration
 *
 * @package Elektra\SeedBundle\Entity\Trainings
 *
 * @ORM\Entity
 * @ORM\Table(name="registrations")
 */
class Registration
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $registrationId;

    /**
     * @var CompanyPerson
     *
     * @ORM\ManyToOne(targetEntity="Person", inversedBy="registrations", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="personId", referencedColumnName="personId")
     */
    protected $person;

    /**
     * @var Training
     *
     * @ORM\ManyToOne(targetEntity="Training", inversedBy="registrations", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="trainingId", referencedColumnName="trainingId")
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
        return $this->registrationId;
    }

    /**
     * @return int
     */
    public function getRegistrationId()
    {
        return $this->registrationId;
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
}