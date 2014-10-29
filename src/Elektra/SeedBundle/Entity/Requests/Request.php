<?php

namespace Elektra\SeedBundle\Entity\Requests;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Elektra\SeedBundle\Entity\AbstractAuditableAnnotableEntity;
use Elektra\SeedBundle\Entity\Auditing\Audit;
use Elektra\SeedBundle\Entity\Companies\CompanyLocation;
use Elektra\SeedBundle\Entity\Companies\CompanyPerson;
use Elektra\SeedBundle\Entity\Companies\PartnerCompany;
use Elektra\SeedBundle\Entity\SeedUnits\SeedUnit;
use Symfony\Component\Validator\Constraints\GreaterThan;

/**
 * @ORM\Entity(repositoryClass="Elektra\SeedBundle\Repository\Requests\RequestRepository")
 * @ORM\Table(name="requests")
 *
 * @ORM\HasLifecycleCallbacks()
 *
 * Unique:
 *      single fields only:
 *          requestNumber
 */
class Request extends AbstractAuditableAnnotableEntity
{

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer", options={"unsigned" = true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $requestId;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=15, unique=true)
     */
    protected $requestNumber;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true, options={"unsigned" = true})
     * @GreaterThan(value = 0)
     */
    protected $numberOfUnitsRequested;

    /**
     * @var PartnerCompany
     *
     * @ORM\ManyToOne(targetEntity="Elektra\SeedBundle\Entity\Companies\PartnerCompany", inversedBy="requests",
     *                                                                            fetch="EXTRA_LAZY")
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
     * @var Collection SeedUnit[]
     *
     * @ORM\OneToMany(targetEntity="Elektra\SeedBundle\Entity\SeedUnits\SeedUnit", mappedBy="request",
     *                                                                             fetch="EXTRA_LAZY")
     */
    protected $seedUnits;

    /**
     * @var Collection Note[]
     *
     * @ORM\ManyToMany(targetEntity = "Elektra\SeedBundle\Entity\Notes\Note", fetch="EXTRA_LAZY", cascade={"persist",
     *                              "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"timestamp" = "DESC"})
     * @ORM\JoinTable(name = "requests_notes",
     *      joinColumns = {@ORM\JoinColumn(name = "requestId", referencedColumnName = "requestId",
     *      onDelete="CASCADE")},
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
     * @ORM\JoinTable(name = "requests_audits",
     *      joinColumns = {@ORM\JoinColumn(name = "requestId", referencedColumnName = "requestId")},
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

        $this->seedUnits = new ArrayCollection();
    }

    /*************************************************************************
     * Getters / Setters
     *************************************************************************/

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
     * @param PartnerCompany $company
     */
    public function setCompany($company)
    {

        $this->company = $company;
    }

    /**
     * @return PartnerCompany
     */
    public function getCompany()
    {

        return $this->company;
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
     * @param SeedUnit $seedUnit
     */
    public function addSeedUnit(SeedUnit $seedUnit)
    {

        $this->getSeedUnits()->add($seedUnit);
    }

    /**
     * @return ArrayCollection
     */
    public function getSeedUnits()
    {

        return $this->seedUnits;
    }

    /*************************************************************************
     * EntityInterface
     *************************************************************************/

    /**
     * {@inheritdoc}
     */
    public function getId()
    {

        return $this->getRequestId();
    }

    /**
     * {@inheritdoc}
     */
    public function getDisplayName()
    {

        return $this->getRequestNumber();
    }

    /*************************************************************************
     * Lifecycle callbacks
     *************************************************************************/

    // none
}