<?php

namespace Elektra\SeedBundle\Entity\Auditing;

use Doctrine\ORM\Mapping as ORM;
use Elektra\UserBundle\Entity\User;

/**
 * Class Audit
 *
 * @package Elektra\SeedBundle\Entity\Auditing
 *
 * @ORM\Entity
 * @ORM\Table(name="audits")
 */
class Audit
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $auditId;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="createdByUserId", referencedColumnName="id", nullable=false)
     */
    protected $createdBy;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="modifiedByUserId", referencedColumnName="id")
     */
    protected $modifiedBy;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $createdAt;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $modifiedAt;

    public function __construct()
    {
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->auditId;
    }

    /**
     * @return int
     */
    public function getAuditId()
    {
        return $this->auditId;
    }

    /**
     * @param int $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return int
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param User $createdBy
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;
    }

    /**
     * @return User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param int $modifiedAt
     */
    public function setModifiedAt($modifiedAt)
    {
        $this->modifiedAt = $modifiedAt;
    }

    /**
     * @return int
     */
    public function getModifiedAt()
    {
        return $this->modifiedAt;
    }

    /**
     * @param User $modifiedBy
     */
    public function setModifiedBy($modifiedBy)
    {
        $this->modifiedBy = $modifiedBy;
    }

    /**
     * @return User
     */
    public function getModifiedBy()
    {
        return $this->modifiedBy;
    }
}