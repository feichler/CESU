<?php

namespace Elektra\SeedBundle\Entity\Requests;

use Doctrine\ORM\Mapping as ORM;
use Elektra\SeedBundle\Entity\Companies\Person;
use Elektra\SeedBundle\Entity\Companies\Address;
use Elektra\SeedBundle\Entity\Companies\PartnerTier;
use Doctrine\Common\Collections\ArrayCollection;
use Elektra\SeedBundle\Entity\Auditing\Audit;
/**
 * Class Request
 *
 * @package Elektra\SeedBundle\Entity\Requests
 *
 * @ORM\Entity(repositoryClass="Elektra\SeedBundle\Repositories\Requests\RequestRepository")
 * @ORM\Table(name="requests")
 */
abstract class Request
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
     * @ORM\ManyToMany(targetEntity = "Elektra\SeedBundle\Entity\Notes\Note", fetch="EXTRA_LAZY", cascade={"persist", "remove"})
     * @ORM\JoinTable(name = "requests_notes",
     *      joinColumns = {@ORM\JoinColumn(name = "requestId", referencedColumnName = "requestId")},
     *      inverseJoinColumns = {@ORM\JoinColumn(name = "noteId", referencedColumnName = "noteId", unique = true)}
     * )
     */
    protected $notes;

    /**
     * @var Audit
     *
     * @ORM\OneToOne(targetEntity="Elektra\SeedBundle\Entity\Auditing\Audit", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="auditId", referencedColumnName="auditId")
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
     * @param \Elektra\SeedBundle\Entity\Companies\Address $companyAddress
     */
    public function setCompanyAddress($companyAddress)
    {
        $this->companyAddress = $companyAddress;
    }

    /**
     * @return \Elektra\SeedBundle\Entity\Companies\Address
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