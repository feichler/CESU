<?php

namespace Elektra\SeedBundle\Entity\Auditing;

use Doctrine\ORM\Mapping as ORM;
use Elektra\SeedBundle\Entity\AbstractEntity;
use Elektra\UserBundle\Entity\User;

/**
 * @ORM\Entity(repositoryClass="Elektra\SeedBundle\Repository\Auditing\AuditRepository")
 * @ORM\Table(name="audits")
 *
 * @ORM\HasLifecycleCallbacks()
 *
 * Unique: nothing
 */
class Audit extends AbstractEntity
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
     * @ORM\ManyToOne(targetEntity="Elektra\UserBundle\Entity\User", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="modifiedByUserId", referencedColumnName="id")
     */
    protected $user;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $timestamp;

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
    public function getAuditId()
    {

        return $this->auditId;
    }

    /**
     * @param int $timestamp
     */
    public function setTimestamp($timestamp)
    {

        $this->timestamp = $timestamp;
    }

    /**
     * @return int
     */
    public function getTimestamp()
    {

        return $this->timestamp;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {

        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser()
    {

        return $this->user;
    }

    /*************************************************************************
     * EntityInterface
     *************************************************************************/

    /**
     * {@inheritdoc}
     */
    public function getId()
    {

        return $this->getAuditId();
    }

    /**
     * {@inheritdoc}
     */
    public function getDisplayName()
    {

        return $this->getUser()->getUsername();
    }

    /*************************************************************************
     * Lifecycle callbacks
     *************************************************************************/

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function ensureTimestamp()
    {

        if (!$this->getTimestamp()) {
            $this->setTimestamp(time());
        }
    }
}