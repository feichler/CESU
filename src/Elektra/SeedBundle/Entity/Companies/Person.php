<?php

namespace Elektra\SeedBundle\Entity\Companies;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Elektra\SeedBundle\Entity\AbstractAuditableAnnotableEntity;
use Elektra\SeedBundle\Entity\Auditing\Audit;

/**
 * @ORM\Entity(repositoryClass="Elektra\SeedBundle\Repository\Companies\PersonRepository")
 * @ORM\Table("persons")
 *
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="personType", type="string")
 *
 * @ORM\HasLifecycleCallbacks()
 *
 * Unique: nothing
 */
class Person extends AbstractAuditableAnnotableEntity
{

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer", options={"unsigned" = true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $personId;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50)
     */
    protected $firstName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50)
     */
    protected $lastName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    protected $jobTitle;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    protected $salutation;

    /**
     * @var Collection ContactInfo[]
     *
     * @ORM\OneToMany(targetEntity="Elektra\SeedBundle\Entity\Companies\ContactInfo", mappedBy="person",
     *                                                                                fetch="EXTRA_LAZY",
     *                                                                                cascade={"remove", "persist"})
     */
    protected $contactInfo;

    /**
     * @var Collection Note[]
     *
     * @ORM\ManyToMany(targetEntity = "Elektra\SeedBundle\Entity\Notes\Note", fetch="EXTRA_LAZY", cascade={"persist",
     *                              "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"timestamp" = "DESC"})
     * @ORM\JoinTable(name = "persons_notes",
     *      joinColumns = {@ORM\JoinColumn(name = "personId", referencedColumnName = "personId", onDelete="CASCADE")},
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
     * @ORM\JoinTable(name = "persons_audits",
     *      joinColumns = {@ORM\JoinColumn(name = "personId", referencedColumnName = "personId")},
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

        $this->contactInfo = new ArrayCollection();
    }

    /*************************************************************************
     * Getters / Setters
     *************************************************************************/

    /**
     * @return int
     */
    public function getPersonId()
    {

        return $this->personId;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {

        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {

        return $this->firstName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName($lastName)
    {

        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {

        return $this->lastName;
    }

    /**
     * @param string $jobTitle
     */
    public function setJobTitle($jobTitle)
    {

        $this->jobTitle = $jobTitle;
    }

    /**
     * @return string
     */
    public function getJobTitle()
    {

        return $this->jobTitle;
    }

    /**
     * @param string $salutation
     */
    public function setSalutation($salutation)
    {

        $this->salutation = $salutation;
    }

    /**
     * @return string
     */
    public function getSalutation()
    {

        return $this->salutation;
    }

    /**
     * @param Collection ContactInfo[] $contactInfo
     */
    public function setContactInfo($contactInfo)
    {

        $this->contactInfo = $contactInfo;
    }

    /**
     * @param ContactInfo $contactInfo
     */
    public function addContactInfo(ContactInfo $contactInfo)
    {

        $this->getContactInfo()->add($contactInfo);
    }

    /**
     * @return Collection ContactInfo[]
     */
    public function getContactInfo()
    {

        return $this->contactInfo;
    }

    /*************************************************************************
     * EntityInterface
     *************************************************************************/

    /**
     * {@inheritdoc}
     */
    public function getId()
    {

        return $this->getPersonId();
    }

    /**
     * {@inheritdoc}
     */
    public function getDisplayName()
    {

        return $this->getFirstName() . ' ' . $this->getLastName();
    }

    /*************************************************************************
     * Lifecycle callbacks
     *************************************************************************/

    // none
}