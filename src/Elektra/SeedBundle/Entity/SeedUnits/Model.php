<?php

namespace Elektra\SeedBundle\Entity\SeedUnits;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Elektra\SeedBundle\Entity\AbstractAuditableEntity;
use Elektra\SeedBundle\Entity\Auditing\Audit;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Elektra\SeedBundle\Repository\SeedUnits\ModelRepository")
 * @ORM\Table(name="models")
 *
 * @ORM\HasLifecycleCallbacks()
 *
 * Unique:
 *      single fields only:
 *          name
 */
class Model extends AbstractAuditableEntity
{

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer", options={"unsigned" = true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $modelId;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50, unique=true)
     *
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
     * @ORM\JoinTable(name = "models_audits",
     *      joinColumns = {@ORM\JoinColumn(name = "modelId", referencedColumnName = "modelId")},
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
    public function getModelId()
    {

        return $this->modelId;
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

        return $this->getModelId();
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