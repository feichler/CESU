<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Entity\Requests;

use Doctrine\ORM\Mapping as ORM;
use Elektra\SeedBundle\Entity\Companies\Person;
use Elektra\SeedBundle\Entity\Companies\Address;
use Elektra\SeedBundle\Entity\Companies\PartnerTier;
use Doctrine\Common\Collections\ArrayCollection;
use Elektra\SeedBundle\Entity\Auditing\Audit;
use Elektra\SeedBundle\Entity\AuditableInterface;
use Elektra\SeedBundle\Entity\AnnotableInterface;
use Elektra\SeedBundle\Entity\CRUDEntityInterface;

/**
 * Class Request
 *
 * @package Elektra\SeedBundle\Entity\Requests
 *
 * @version 0.1-dev
 *
 * @ORM\Entity(repositoryClass="Elektra\SeedBundle\Repositories\Requests\RequestRepository")
 * @ORM\Table(name="requests")
 */
abstract class Request implements AuditableInterface, AnnotableInterface, CRUDEntityInterface
{

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $requestId;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=10, unique=true)
     */
    protected $requestNumber;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $numberOfUnitsRequested;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $tocAgreedAt;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $objectivesAgreedAt;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=15)
     */
    protected $clientIpAddress;

    /**
     * @var PartnerTier
     *
     * @ORM\ManyToOne(targetEntity="Elektra\SeedBundle\Entity\Companies\PartnerTier", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="partnerTierId", referencedColumnName="partnerTierId")
     */
    protected $partnerTier;

    /**
     * @var RequestStatus
     *
     * @ORM\ManyToOne(targetEntity="RequestStatus", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="requestStatusId", referencedColumnName="requestStatusId", nullable=false)
     */
    protected $requestStatus;

    /**
     * @var Person
     *
     * @ORM\ManyToOne(targetEntity="Elektra\SeedBundle\Entity\Companies\Person", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="requesterPersonId", referencedColumnName="personId")
     */
    protected $requesterPerson;

    /**
     * @var Person
     *
     * @ORM\ManyToOne(targetEntity="Elektra\SeedBundle\Entity\Companies\Person", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="receiverPersonId", referencedColumnName="personId")
     */
    protected $receiverPerson;

    /**
     * @var Address
     *
     * @ORM\ManyToOne(targetEntity="Elektra\SeedBundle\Entity\Companies\Address", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="companyAddressId", referencedColumnName="addressId")
     */
    protected $companyAddress;

    /**
     * @var Address
     *
     * @ORM\ManyToOne(targetEntity="Elektra\SeedBundle\Entity\Companies\Address", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="shippingAddressId", referencedColumnName="addressId")
     */
    protected $shippingAddress;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity = "Elektra\SeedBundle\Entity\Notes\Note", fetch="EXTRA_LAZY", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"timestamp" = "DESC"})
     * @ORM\JoinTable(name = "requests_notes",
     *      joinColumns = {@ORM\JoinColumn(name = "requestId", referencedColumnName = "requestId", onDelete="CASCADE")},
     *      inverseJoinColumns = {@ORM\JoinColumn(name = "noteId", referencedColumnName = "noteId", unique = true, onDelete="CASCADE")}
     * )
     */
    protected $notes;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity = "Elektra\SeedBundle\Entity\Auditing\Audit", fetch="EXTRA_LAZY", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"timestamp" = "DESC"})
     * @ORM\JoinTable(name = "request_audits",
     *      joinColumns = {@ORM\JoinColumn(name = "requestId", referencedColumnName = "requestId")},
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

        return $this->requestId;
    }

    /**
     * @return int
     */
    public function getRequestId()
    {

        return $this->requestId;
    }

    /**
     * @param string $name
     */
    public function setRequestNumber($name)
    {

        $this->requestNumber = $name;
    }

    /**
     * @return string
     */
    public function getRequestNumber()
    {

        return $this->requestNumber;
    }

    /**
     * @param string $clientIpAddress
     */
    public function setClientIpAddress($clientIpAddress)
    {

        $this->clientIpAddress = $clientIpAddress;
    }

    /**
     * @return string
     */
    public function getClientIpAddress()
    {

        return $this->clientIpAddress;
    }

    /**
     * @param Address $companyAddress
     */
    public function setCompanyAddress($companyAddress)
    {

        $this->companyAddress = $companyAddress;
    }

    /**
     * @return Address
     */
    public function getCompanyAddress()
    {

        return $this->companyAddress;
    }

    /**
     * @param int $numberOfUnitsRequested
     */
    public function setNumberOfUnitsRequested($numberOfUnitsRequested)
    {

        $this->numberOfUnitsRequested = $numberOfUnitsRequested;
    }

    /**
     * @return int
     */
    public function getNumberOfUnitsRequested()
    {

        return $this->numberOfUnitsRequested;
    }

    /**
     * @param int $objectivesAgreedAt
     */
    public function setObjectivesAgreedAt($objectivesAgreedAt)
    {

        $this->objectivesAgreedAt = $objectivesAgreedAt;
    }

    /**
     * @return int
     */
    public function getObjectivesAgreedAt()
    {

        return $this->objectivesAgreedAt;
    }

    /**
     * @param PartnerTier $partnerTier
     */
    public function setPartnerTier($partnerTier)
    {

        $this->partnerTier = $partnerTier;
    }

    /**
     * @return PartnerTier
     */
    public function getPartnerTier()
    {

        return $this->partnerTier;
    }

    /**
     * @param Person $receiverPerson
     */
    public function setReceiverPerson($receiverPerson)
    {

        $this->receiverPerson = $receiverPerson;
    }

    /**
     * @return Person
     */
    public function getReceiverPerson()
    {

        return $this->receiverPerson;
    }

    /**
     * @param RequestStatus $requestStatus
     */
    public function setRequestStatus($requestStatus)
    {

        $this->requestStatus = $requestStatus;
    }

    /**
     * @return RequestStatus
     */
    public function getRequestStatus()
    {

        return $this->requestStatus;
    }

    /**
     * @param Person $requesterPerson
     */
    public function setRequesterPerson($requesterPerson)
    {

        $this->requesterPerson = $requesterPerson;
    }

    /**
     * @return Person
     */
    public function getRequesterPerson()
    {

        return $this->requesterPerson;
    }

    /**
     * @param Address $shippingAddress
     */
    public function setShippingAddress($shippingAddress)
    {

        $this->shippingAddress = $shippingAddress;
    }

    /**
     * @return Address
     */
    public function getShippingAddress()
    {

        return $this->shippingAddress;
    }

    /**
     * @param int $tocAgreedAt
     */
    public function setTocAgreedAt($tocAgreedAt)
    {

        $this->tocAgreedAt = $tocAgreedAt;
    }

    /**
     * @return int
     */
    public function getTocAgreedAt()
    {

        return $this->tocAgreedAt;
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

        return $audits->count() > 1 ? $audits->slice($audits->count() - 1, 1)[0] : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getTitle()
    {
        return $this->getRequestNumber();
    }
}