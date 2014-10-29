<?php

namespace Elektra\SeedBundle\Entity\Companies;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Elektra\SeedBundle\Entity\AbstractAuditableAnnotableEntity;
use Elektra\SeedBundle\Entity\Auditing\Audit;

/**
 *
 * @ORM\Entity(repositoryClass="Elektra\SeedBundle\Repository\Companies\ContactInfoRepository")
 * @ORM\Table(name="contact_info")
 *
 * @ORM\HasLifecycleCallbacks()
 *
 * Unique:
 *      combined:
 *          name, person, contactInfoType (only one named contact info per person / type)
 */
class ContactInfo extends AbstractAuditableAnnotableEntity
{

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer", options={"unsigned" = true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $contactInfoId;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $text;

    /**
     * @var Person
     *
     * @ORM\ManyToOne(targetEntity="Elektra\SeedBundle\Entity\Companies\Person", inversedBy="contactInfo",
     *                                                                           fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="personId", referencedColumnName="personId", nullable=false)
     */
    protected $person;

    /**
     * @var ContactInfoType
     *
     * @ORM\ManyToOne(targetEntity="Elektra\SeedBundle\Entity\Companies\ContactInfoType", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="contactInfoTypeId", referencedColumnName="contactInfoTypeId")
     */
    protected $contactInfoType;

    /**
     * @var Collection Note[]
     *
     * @ORM\ManyToMany(targetEntity = "Elektra\SeedBundle\Entity\Notes\Note", fetch="EXTRA_LAZY", cascade={"persist",
     *                              "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"timestamp" = "DESC"})
     * @ORM\JoinTable(name = "contact_info_notes",
     *      joinColumns = {@ORM\JoinColumn(name = "contactInfoId", referencedColumnName = "contactInfoId",
     *      onDelete="CASCADE")}, inverseJoinColumns = {@ORM\JoinColumn(name = "noteId", referencedColumnName =
     *      "noteId", unique = true, onDelete="CASCADE")}
     * )
     */
    protected $notes;

    /**
     * @var Collection Audit[]
     *
     * @ORM\ManyToMany(targetEntity = "Elektra\SeedBundle\Entity\Auditing\Audit", fetch="EXTRA_LAZY",
     *                              cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"timestamp" = "DESC"})
     * @ORM\JoinTable(name = "contact_info_audits",
     *      joinColumns = {@ORM\JoinColumn(name = "contactInfoId", referencedColumnName = "contactInfoId")},
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
    }

    /*************************************************************************
     * Getters / Setters
     *************************************************************************/

    /**
     * @return int
     */
    public function getContactInfoId()
    {

        return $this->contactInfoId;
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
     * @param string $text
     */
    public function setText($text)
    {

        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getText()
    {

        return $this->text;
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
     * @param ContactInfoType $contactInfoType
     */
    public function setContactInfoType($contactInfoType)
    {

        $this->contactInfoType = $contactInfoType;
    }

    /**
     * @return ContactInfoType
     */
    public function getContactInfoType()
    {

        return $this->contactInfoType;
    }

    /*************************************************************************
     * EntityInterface
     *************************************************************************/

    /**
     * {@inheritdoc}
     */
    public function getId()
    {

        return $this->getContactInfoId();
    }

    /**
     * {@inheritdoc}
     */
    public function getDisplayName()
    {

        return $this->getName();
    }

    // nothing

    /*************************************************************************
     * Lifecycle callbacks
     *************************************************************************/

    // none
}