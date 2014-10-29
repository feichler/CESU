<?php

namespace Elektra\SeedBundle\Entity\Companies;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Elektra\SeedBundle\Entity\AbstractAuditableEntity;
use Elektra\SeedBundle\Entity\Auditing\Audit;

/**
 *
 * @ORM\Entity(repositoryClass="Elektra\SeedBundle\Repository\Companies\PartnerTypeRepository")
 * @ORM\Table(name="partner_types")
 *
 * @ORM\HasLifecycleCallbacks()
 *
 * Unique:
 *      single fields:
 *          name
 *          alias
 */
class PartnerType extends AbstractAuditableEntity
{

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $partnerTypeId;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50, unique=true)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=10, unique=true)
     */
    protected $alias;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $comment;

    /**
     * @var Collection Audit[]
     *
     * @ORM\ManyToMany(targetEntity = "Elektra\SeedBundle\Entity\Auditing\Audit", fetch="EXTRA_LAZY",
     *                              cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"timestamp" = "DESC"})
     * @ORM\JoinTable(name = "partner_types_audits",
     *      joinColumns = {@ORM\JoinColumn(name = "partnerTypeId", referencedColumnName = "partnerTypeId")},
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
    public function getPartnerTypeId()
    {

        return $this->partnerTypeId;
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
     * @param string $alias
     */
    public function setAlias($alias)
    {

        $this->alias = $alias;
    }

    /**
     * @return string
     */
    public function getAlias()
    {

        return $this->alias;
    }

    /**
     * @param string $comment
     */
    public function setComment($comment)
    {

        $this->comment = $comment;
    }

    /**
     * @return string
     */
    public function getComment()
    {

        return $this->comment;
    }

    /*************************************************************************
     * EntityInterface
     *************************************************************************/

    /**
     * {@inheritdoc}
     */
    public function getId()
    {

        return $this->getPartnerTypeId();
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