<?php

namespace Elektra\SeedBundle\Entity\Companies;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Elektra\SeedBundle\Entity\AbstractAuditableAnnotableEntity;
use Elektra\SeedBundle\Entity\Auditing\Audit;
use Elektra\SeedBundle\Entity\SeedUnits\SeedUnit;

/**
 * @ORM\Entity
 * @ORM\Table(name="locations")
 *
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="locationType", type="string")
 *
 * @ORM\HasLifecycleCallbacks()
 *
 * Unique: defined by inheriting classes (name within inheriting)
 */
abstract class AbstractLocation extends AbstractAuditableAnnotableEntity
{

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer", options={"unsigned" = true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $locationId;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $name;

    /**
     * NOTE used differently by various inheriting classes
     *
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $alias;

    /**
     * @var Collection SeedUnit[]
     *
     * @ORM\OneToMany(targetEntity="Elektra\SeedBundle\Entity\SeedUnits\SeedUnit", mappedBy="location",
     *                                                                             fetch="EXTRA_LAZY")
     */
    protected $seedUnits;

    /**
     * @var Collection Note[]
     *
     * @ORM\ManyToMany(targetEntity = "Elektra\SeedBundle\Entity\Notes\Note", fetch="EXTRA_LAZY", cascade={"persist",
     *                              "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"timestamp" = "DESC"})
     * @ORM\JoinTable(name = "locations_notes",
     *      joinColumns = {@ORM\JoinColumn(name = "locationId", referencedColumnName = "locationId")},
     *      inverseJoinColumns = {@ORM\JoinColumn(name = "noteId", referencedColumnName = "noteId", unique = true)}
     * )
     */
    protected $notes;

    /**
     * @var Collection Audit[]
     *
     * @ORM\ManyToMany(targetEntity = "Elektra\SeedBundle\Entity\Auditing\Audit", fetch="EXTRA_LAZY",
     *                              cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"timestamp" = "DESC"})
     * @ORM\JoinTable(name = "locations_audits",
     *      joinColumns = {@ORM\JoinColumn(name = "locationId", referencedColumnName = "locationId")},
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
     * @param int $locationId
     */
    public function setLocationId($locationId)
    {

        $this->locationId = $locationId;
    }

    /**
     * @return int
     */
    public function getLocationId()
    {

        return $this->locationId;
    }

    /**
     * @param string $shortName
     */
    public function setName($shortName)
    {

        $this->name = $shortName;
    }

    /**
     * @return string
     */
    public function getName()
    {

        return $this->name;
    }

    /**
     * @return string
     */
    public function getAlias()
    {

        return $this->alias;
    }

    /**
     * @param string $internalName
     */
    public function setAlias($internalName)
    {

        $this->alias = $internalName;
    }

    /**
     * @param Collection SeedUnit[] $seedUnits
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
     * @return Collection SeedUnit[]
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

        return $this->getLocationId();
    }

    /**
     * {@inheritdoc}
     */
    public function getDisplayName()
    {

        return $this->getName();
    }

    /*************************************************************************
     * Lifecycle callbacks
     *************************************************************************/

    // none
}