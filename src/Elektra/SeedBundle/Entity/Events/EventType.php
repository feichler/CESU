<?php

namespace Elektra\SeedBundle\Entity\Events;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Elektra\SeedBundle\Entity\AbstractAuditableEntity;
use Elektra\SeedBundle\Entity\Auditing\Audit;

/**
 * @ORM\Entity(repositoryClass="Elektra\SeedBundle\Repository\Events\EventTypeRepository")
 * @ORM\Table(name="events_types")
 *
 * @ORM\HasLifecycleCallbacks()
 *
 * Unique:
 *      single fields only:
 *          name
 *          internalName
 */
class EventType extends AbstractAuditableEntity
{

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer", options={"unsigned" = true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $eventTypeId;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, unique=true)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, unique=true)
     */
    protected $internalName;

    /**
     * @var Collection Audit[]
     *
     * @ORM\ManyToMany(targetEntity = "Elektra\SeedBundle\Entity\Auditing\Audit", fetch="EXTRA_LAZY",
     *                              cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"timestamp" = "DESC"})
     * @ORM\JoinTable(name = "events_types_audits",
     *      joinColumns = {@ORM\JoinColumn(name = "eventTypeId", referencedColumnName = "eventTypeId")},
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
    public function getEventTypeId()
    {

        return $this->eventTypeId;
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
     * @param string $internalName
     */
    public function setInternalName($internalName)
    {

        $this->internalName = $internalName;
    }

    /**
     * @return string
     */
    public function getInternalName()
    {

        return $this->internalName;
    }

    /*************************************************************************
     * EntityInterface
     *************************************************************************/

    /**
     * {@inheritdoc}
     */
    public function getId()
    {

        return $this->getEventTypeId();
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