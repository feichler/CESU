<?php

namespace Elektra\SeedBundle\Entity\SeedUnits;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Elektra\SeedBundle\Entity\AbstractAuditableAnnotableEntity;
use Elektra\SeedBundle\Entity\Auditing\Audit;
use Elektra\SeedBundle\Entity\Companies\AbstractLocation;
use Elektra\SeedBundle\Entity\Events\Event;
use Elektra\SeedBundle\Entity\Requests\Request;

/**
 *
 * @ORM\Entity(repositoryClass="Elektra\SeedBundle\Repository\SeedUnits\SeedUnitRepository")
 * @ORM\Table(name="seed_units")
 *
 * @ORM\HasLifecycleCallbacks()
 *
 * Unique:
 *      single fields only:
 *          serialNumber
 */
class SeedUnit extends AbstractAuditableAnnotableEntity
{

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $seedUnitId;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=75, unique=true)
     */
    protected $serialNumber;

    /**
     * @var Model
     *
     * @ORM\ManyToOne(targetEntity="Model", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="modelId", referencedColumnName="modelId", nullable=false)
     */
    protected $model;

    /**
     * @var PowerCordType
     *
     * @ORM\ManyToOne(targetEntity="PowerCordType", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="powerCordTypeId", referencedColumnName="powerCordTypeId", nullable=false)
     */
    protected $powerCordType;

    /**
     * @var Collection Event[]
     *
     * @ORM\OneToMany(targetEntity="Elektra\SeedBundle\Entity\Events\Event", mappedBy="seedUnit", fetch="EXTRA_LAZY",
     *                                                                       cascade={"persist", "remove"})
     * @ORM\OrderBy({"timestamp" = "DESC"})
     * )
     */
    protected $events;

    /**
     * @var Request
     *
     * @ORM\ManyToOne(targetEntity="Elektra\SeedBundle\Entity\Requests\Request", inversedBy="seedUnits",
     *                                                                           fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="requestId", referencedColumnName="requestId")
     */
    protected $request;

    /**
     * @var AbstractLocation
     *
     * @ORM\ManyToOne(targetEntity="Elektra\SeedBundle\Entity\Companies\AbstractLocation", inversedBy="seedUnits",
     *                                                                             fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="locationId", referencedColumnName="locationId", nullable=false)
     */
    protected $location;

    /**
     * @var StatusShipping
     *
     * @ORM\ManyToOne(targetEntity="Elektra\SeedBundle\Entity\SeedUnits\StatusShipping", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="statusShippingId", referencedColumnName="statusId", nullable=false)
     */
    protected $statusShipping;

    /**
     * @var StatusUsage
     *
     * @ORM\ManyToOne(targetEntity="Elektra\SeedBundle\Entity\SeedUnits\StatusUsage", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="statusUsageId", referencedColumnName="statusId")
     */
    protected $statusUsage;

    /**
     * @var StatusSales
     *
     * @ORM\ManyToOne(targetEntity="Elektra\SeedBundle\Entity\SeedUnits\StatusSales", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="statusSalesId", referencedColumnName="statusId")
     */
    protected $statusSales;

    /**
     * @var Collection Note[]
     *
     * @ORM\ManyToMany(targetEntity = "Elektra\SeedBundle\Entity\Notes\Note", fetch="EXTRA_LAZY", cascade={"persist",
     *                              "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"timestamp" = "DESC"})
     * @ORM\JoinTable(name = "seed_units_notes",
     *      joinColumns = {@ORM\JoinColumn(name = "seedUnitId", referencedColumnName = "seedUnitId",
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
     * @ORM\JoinTable(name = "seed_units_audits",
     *      joinColumns = {@ORM\JoinColumn(name = "seedUnitId", referencedColumnName = "seedUnitId")},
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

        $this->events = new ArrayCollection();
    }

    /*************************************************************************
     * Getters / Setters
     *************************************************************************/

    /**
     * @return int
     */
    public function getSeedUnitId()
    {

        return $this->seedUnitId;
    }

    /**
     * @param string $serialNumber
     */
    public function setSerialNumber($serialNumber)
    {

        $this->serialNumber = $serialNumber;
    }

    /**
     * @return string
     */
    public function getSerialNumber()
    {

        return $this->serialNumber;
    }

    /**
     * @param Model $model
     */
    public function setModel($model)
    {

        $this->model = $model;
    }

    /**
     * @return Model
     */
    public function getModel()
    {

        return $this->model;
    }

    /**
     * @param PowerCordType $powerCordType
     */
    public function setPowerCordType($powerCordType)
    {

        $this->powerCordType = $powerCordType;
    }

    /**
     * @return PowerCordType
     */
    public function getPowerCordType()
    {

        return $this->powerCordType;
    }

    /**
     * @param Collection Event[] $events
     */
    public function setEvents($events)
    {

        $this->events = $events;
    }

    /**
     * @param Event $event
     */
    public function addEvent(Event $event)
    {

        $this->getEvents()->add($event);
    }

    /**
     * @return Collection Event[]
     */
    public function getEvents()
    {

        return $this->events;
    }

    /**
     * @param Request $request
     */
    public function setRequest($request)
    {

        $this->request = $request;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {

        return $this->request;
    }

    /**
     * @param AbstractLocation $location
     */
    public function setLocation($location)
    {

        $this->location = $location;
    }

    /**
     * @return AbstractLocation
     */
    public function getLocation()
    {

        return $this->location;
    }

    /**
     * @param StatusShipping $statusShipping
     */
    public function setStatusShipping($statusShipping)
    {

        $this->statusShipping = $statusShipping;
    }

    /**
     * @return StatusShipping
     */
    public function getStatusShipping()
    {

        return $this->statusShipping;
    }

    /**
     * @param StatusUsage $statusUsage
     */
    public function setStatusUsage($statusUsage)
    {

        $this->statusUsage = $statusUsage;
    }

    /**
     * @return StatusUsage
     */
    public function getStatusUsage()
    {

        return $this->statusUsage;
    }

    /**
     * @param StatusSales $statusSales
     */
    public function setStatusSales($statusSales)
    {

        $this->statusSales = $statusSales;
    }

    /**
     * @return StatusSales
     */
    public function getStatusSales()
    {

        return $this->statusSales;
    }

    /*************************************************************************
     * EntityInterface
     *************************************************************************/

    /**
     * {@inheritdoc}
     */
    public function getId()
    {

        return $this->getSeedUnitId();
    }

    /**
     * {@inheritdoc}
     */
    public function getDisplayName()
    {

        return $this->getSerialNumber();
    }

    /*************************************************************************
     * Lifecycle callbacks
     *************************************************************************/

    // none
}