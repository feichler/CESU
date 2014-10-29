<?php

namespace Elektra\SeedBundle\Entity\SeedUnits;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Elektra\SeedBundle\Entity\AbstractAuditableEntity;
use Elektra\SeedBundle\Entity\Auditing\Audit;

/**
 * @ORM\Entity(repositoryClass="Elektra\SeedBundle\Repository\SeedUnits\PowerCordTypeRepository")
 * @ORM\Table(name="power_cord_types")
 *
 * @ORM\HasLifecycleCallbacks()
 *
 * Unique:
 *      single fields only:
 *          name
 */
class PowerCordType extends AbstractAuditableEntity
{

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer", options={"unsigned" = true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $powerCordTypeId;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50, unique=true)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @var Collection Audit[]
     *
     * @ORM\ManyToMany(targetEntity = "Elektra\SeedBundle\Entity\Auditing\Audit", fetch="EXTRA_LAZY",
     *                              cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"timestamp" = "DESC"})
     * @ORM\JoinTable(name = "power_cord_types_audits",
     *      joinColumns = {@ORM\JoinColumn(name = "powerCordTypeId", referencedColumnName = "powerCordTypeId")},
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
    public function getPowerCordTypeId()
    {

        return $this->powerCordTypeId;
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
     * @param string $description
     */
    public function setDescription($description)
    {

        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {

        return $this->description;
    }

    /*************************************************************************
     * EntityInterface
     *************************************************************************/

    /**
     * {@inheritdoc}
     */
    public function getId()
    {

        return $this->getPowerCordTypeId();
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