<?php

namespace Elektra\SeedBundle\Entity\Companies;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Elektra\SeedBundle\Entity\Auditing\Audit;
use Elektra\SeedBundle\Entity\AuditableInterface;
use Elektra\SeedBundle\Entity\AnnotableInterface;

/**
 * Class ContactInfo
 *
 * @package Elektra\SeedBundle\Entity\Companies
 *
 * @ORM\Entity
 * @ORM\Table(name="contactInfo")
 */
class ContactInfo implements AuditableInterface, AnnotableInterface
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $contactInfoId;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50, unique=true)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, unique=true)
     */
    protected $text;

    /**
     * @var ContactInfoType
     *
     * @ORM\ManyToOne(targetEntity="ContactInfoType", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="contactInfoTypeId", referencedColumnName="contactInfoTypeId")
     */
    protected $contactInfoType;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity = "Elektra\SeedBundle\Entity\Notes\Note", fetch="EXTRA_LAZY", cascade={"persist", "remove"})
     * @ORM\JoinTable(name = "contactInfo_notes",
     *      joinColumns = {@ORM\JoinColumn(name = "contactInfoId", referencedColumnName = "contactInfoId")},
     *      inverseJoinColumns = {@ORM\JoinColumn(name = "noteId", referencedColumnName = "noteId", unique = true)}
     * )
     */
    protected $notes;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity = "Elektra\SeedBundle\Entity\Auditing\Audit", fetch="EXTRA_LAZY", cascade={"persist", "remove"})
     * @ORM\JoinTable(name = "contactInfo_audits",
     *      joinColumns = {@ORM\JoinColumn(name = "contactInfoId", referencedColumnName = "contactInfoId")},
     *      inverseJoinColumns = {@ORM\JoinColumn(name = "auditId", referencedColumnName = "auditId", unique = true)}
     */
    protected $audits;

    public function __construct()
    {
        $this->notes = new ArrayCollection();
        $this->audits = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->contactInfoId;
    }

    /**
     * @return int
     */
    public function getContactInfoId()
    {
        return $this->contactInfoId;
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
}