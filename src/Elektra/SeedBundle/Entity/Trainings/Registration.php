<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Entity\Trainings;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Elektra\CrudBundle\Entity\EntityInterface as CrudInterface;
use Elektra\SeedBundle\Auditing\Helper;
use Elektra\SeedBundle\Entity\AnnotableInterface;
use Elektra\SeedBundle\Entity\AuditableInterface;
use Elektra\SeedBundle\Entity\Auditing\Audit;
use Elektra\SeedBundle\Entity\Companies\CompanyPerson;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class Registration
 *
 * @package Elektra\SeedBundle\Entity\Trainings
 *
 * @version 0.1-dev
 *
 * @ORM\Entity(repositoryClass="Elektra\SeedBundle\Repository\Trainings\RegistrationRepository")
 * @ORM\Table(name="registrations")
 * @UniqueEntity(fields={ "training", "person" }, message="")
 */
class Registration implements AuditableInterface, AnnotableInterface, CrudInterface
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
     * @ORM\ManyToOne(targetEntity="Elektra\SeedBundle\Entity\Companies\CompanyPerson", inversedBy="registrations", fetch="EXTRA_LAZY")
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
     * @ORM\ManyToMany(targetEntity = "Elektra\SeedBundle\Entity\Notes\Note", fetch="EXTRA_LAZY", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"timestamp" = "DESC"})
     * @ORM\JoinTable(name = "registrations_notes",
     *      joinColumns = {@ORM\JoinColumn(name = "registrationId", referencedColumnName = "registrationId", onDelete="CASCADE")},
     *      inverseJoinColumns = {@ORM\JoinColumn(name = "noteId", referencedColumnName = "noteId", unique = true, onDelete="CASCADE")}
     * )
     */
    protected $notes;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity = "Elektra\SeedBundle\Entity\Auditing\Audit", fetch="EXTRA_LAZY", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"timestamp" = "DESC"})
     * @ORM\JoinTable(name = "registrations_audits",
     *      joinColumns = {@ORM\JoinColumn(name = "registrationId", referencedColumnName = "registrationId")},
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
        return Helper::getFirstAudit($this->getAudits());

    }

    /**
     * {@inheritdoc}
     */
    public function getLastModifiedAudit()
    {
        return Helper::getLastAudit($this->getAudits());

    }

    /**
     * Return the representative title of the entity
     *
     * @return string
     */
    public function getTitle()
    {

        //TODO: define title (there actually is no primary field)
        return "???";
    }
}