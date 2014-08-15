<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Entity\Auditing;

use Doctrine\ORM\Mapping as ORM;
use Elektra\SeedBundle\Entity\EntityInterface;
use Elektra\UserBundle\Entity\User;

/**
 * Class Audit
 *
 * @package Elektra\SeedBundle\Entity\Auditing
 *
 * @version 0.1-dev
 *
 * @ORM\Entity
 * @ORM\Table(name="audits")
 */
class Audit implements EntityInterface
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
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $timestamp;

    /**
     *
     */
    public function __construct()
    {
    }

    /**
     * {@inheritdoc}
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
}