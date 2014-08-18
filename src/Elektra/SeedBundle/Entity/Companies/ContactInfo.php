<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Entity\Companies;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Elektra\SeedBundle\Entity\Auditing\Audit;
use Elektra\SeedBundle\Entity\AuditableInterface;
use Elektra\SeedBundle\Entity\AnnotableInterface;
use Elektra\SeedBundle\Entity\CRUDEntityInterface;

/**
 * Class ContactInfo
 *
 * @package Elektra\SeedBundle\Entity\Companies
 *
 *          @version 0.1-dev
 *
 * @ORM\Entity
 * @ORM\Table(name="contactInfo")
 */
class ContactInfo implements AuditableInterface, AnnotableInterface, CRUDEntityInterface
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
     * @var Person
     *
     * @ORM\ManyToOne(targetEntity="Person", inversedBy="contactInfo", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="personId", referencedColumnName="personId", nullable=false)
     */
    protected $contact;

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
     * @ORM\ManyToMany(targetEntity = "Elektra\SeedBundle\Entity\Notes\Note", fetch="EXTRA_LAZY", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"timestamp" = "DESC"})
     * @ORM\JoinTable(name = "contactInfo_notes",
     *      joinColumns = {@ORM\JoinColumn(name = "contactInfoId", referencedColumnName = "contactInfoId", onDelete="CASCADE")},
     *      inverseJoinColumns = {@ORM\JoinColumn(name = "noteId", referencedColumnName = "noteId", unique = true, onDelete="CASCADE")}
     * )
     */
    protected $notes;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity = "Elektra\SeedBundle\Entity\Auditing\Audit", fetch="EXTRA_LAZY", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"timestamp" = "DESC"})
     * @ORM\JoinTable(name = "contactInfo_audits",
     *      joinColumns = {@ORM\JoinColumn(name = "contactInfoId", referencedColumnName = "contactInfoId")},
     *      inverseJoinColumns = {@ORM\JoinColumn(name = "auditId", referencedColumnName = "auditId", unique = true, onDelete="CASCADE")}
     * )
     */
    protected $audits;

    /**
     *
     */
    public function __construct()
    {
        $this->notes = new ArrayCollection();
        $this->audits = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
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
        return $this->getAudits()->slice(0, 1)[0];
    }

    /**
     * {@inheritdoc}
     */
    public function getLastModifiedAudit()
    {
        $audits = $this->getAudits();
        return $audits->count() > 1 ? $audits->slice($audits->count()-1, 1)[0] : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getTitle()
    {
        return $this->getName();
    }
}