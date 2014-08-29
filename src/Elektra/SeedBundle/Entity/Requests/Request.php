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
use Elektra\CrudBundle\Entity\EntityInterface as CrudInterface;
use Elektra\SeedBundle\Entity\Companies\CompanyLocation;
use Elektra\SeedBundle\Entity\Companies\CompanyPerson;
use Doctrine\Common\Collections\ArrayCollection;
use Elektra\SeedBundle\Entity\Auditing\Audit;
use Elektra\SeedBundle\Entity\AuditableInterface;
use Elektra\SeedBundle\Entity\AnnotableInterface;
use Elektra\SeedBundle\Entity\Companies\RequestingCompany;
use Symfony\Component\Validator\Constraints\GreaterThan;

/**
 * Class Requests
 *
 * @package Elektra\SeedBundle\Entity\Requests
 *
 * @version 0.1-dev
 *
 * @ORM\Entity(repositoryClass="Elektra\SeedBundle\Repository\Requests\RequestRepository")
 * @ORM\Table(name="requests")
 * @ORM\HasLifecycleCallbacks
 */
class Request implements AuditableInterface, AnnotableInterface, CrudInterface
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
     * @GreaterThan(value = 0)
     */
    protected $numberOfUnitsRequested;

    /**
     * @var RequestStatus
     *
     * @ORM\ManyToOne(targetEntity="RequestStatus", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="requestStatusId", referencedColumnName="requestStatusId")
     */
    protected $requestStatus;

    /**
     * @var RequestingCompany
     *
     * @ORM\ManyToOne(targetEntity="Elektra\SeedBundle\Entity\Companies\RequestingCompany", inversedBy="requests", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="companyId", referencedColumnName="companyId")
     */
    protected $company;

    /**
     * @var CompanyPerson
     *
     * @ORM\ManyToOne(targetEntity="Elektra\SeedBundle\Entity\Companies\CompanyPerson", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="requesterPersonId", referencedColumnName="personId")
     */
    protected $requesterPerson;

    /**
     * @var CompanyPerson
     *
     * @ORM\ManyToOne(targetEntity="Elektra\SeedBundle\Entity\Companies\CompanyPerson", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="receiverPersonId", referencedColumnName="personId")
     */
    protected $receiverPerson;

    /**
     * @var CompanyLocation
     *
     * @ORM\ManyToOne(targetEntity="Elektra\SeedBundle\Entity\Companies\CompanyLocation", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="shippingLocationId", referencedColumnName="locationId")
     */
    protected $shippingLocation;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Elektra\SeedBundle\Entity\SeedUnits\SeedUnit", mappedBy="request", fetch="EXTRA_LAZY")
     */
    protected $seedUnits;

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
     * @ORM\JoinTable(name = "requests_audits",
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
        $this->seedUnits = new ArrayCollection();
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
     * @param CompanyPerson $receiverPerson
     */
    public function setReceiverPerson($receiverPerson)
    {

        $this->receiverPerson = $receiverPerson;
    }

    /**
     * @return CompanyPerson
     */
    public function getReceiverPerson()
    {

        return $this->receiverPerson;
    }

    /**
     * @param CompanyPerson $requesterPerson
     */
    public function setRequesterPerson($requesterPerson)
    {

        $this->requesterPerson = $requesterPerson;
    }

    /**
     * @return CompanyPerson
     */
    public function getRequesterPerson()
    {

        return $this->requesterPerson;
    }

    /**
     * @param CompanyLocation $shippingLocation
     */
    public function setShippingLocation($shippingLocation)
    {

        $this->shippingLocation = $shippingLocation;
    }

    /**
     * @return CompanyLocation
     */
    public function getShippingLocation()
    {

        return $this->shippingLocation;
    }

    /**
     * @param ArrayCollection $seedUnits
     */
    public function setSeedUnits($seedUnits)
    {

        $this->seedUnits = $seedUnits;
    }

    /**
     * @return ArrayCollection
     */
    public function getSeedUnits()
    {

        return $this->seedUnits;
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

        $audits = $this->getAudits()->slice(0, 1);

        return $audits[0];
    }

    /**
     * {@inheritdoc}
     */
    public function getLastModifiedAudit()
    {

        $audits = $this->getAudits();
        if ($audits->count() > 1) {
            $audits = $audits->slice($audits->count() - 1, 1);

            return $audits[0];
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getTitle()
    {

        return $this->getRequestNumber();
    }

    /**
     * @param \Elektra\SeedBundle\Entity\Companies\Company $company
     */
    public function setCompany($company)
    {
        $this->company = $company;
    }

    /**
     * @return \Elektra\SeedBundle\Entity\Companies\Company
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $tmp = (string)time();
        $this->requestNumber=substr($tmp,  strlen($tmp)-10);
    }
}