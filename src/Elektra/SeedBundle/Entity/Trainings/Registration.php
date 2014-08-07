<?php

namespace Elektra\SeedBundle\Entity\Trainings;

use Doctrine\ORM\Mapping as ORM;
use Elektra\SeedBundle\Entity\Companies\CompanyPerson;
use Doctrine\Common\Collections\ArrayCollection;
use Elektra\SeedBundle\Entity\Auditing\Audit;
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
     * @ORM\JoinColumn(name="personId", referencedColumnName="personId", nullable=false)
     */
    protected $person;

    /**
     * @var Training
     *
     * @ORM\ManyToOne(targetEntity="Training", inversedBy="registrations", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="trainingId", referencedColumnName="trainingId", nullable=false)
     */
    protected $training;

    /**
     * @var ArrayCollection
     *
     * @ManyToMany(targetEntity = "Note", cascade={"persist", "remove"})
     * @JoinTable(name = "registrations_notes",
     *      joinColumns = {@JoinColumn(name = "registrationId", referencedColumnName = "registrationId")},
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