<?php

namespace Elektra\SeedBundle\Entity\Requests;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Elektra\SeedBundle\Entity\Auditing\Audit;
use Elektra\SeedBundle\Entity\AuditableInterface;

/**
 * Class RequestStatus
 *
 * @package Elektra\SeedBundle\Entity\Requests
 *
 * @ORM\Entity
 * @ORM\Table(name="requestStatuses")
 */
class RequestStatus implements AuditableInterface
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $requestStatusId;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50, unique=true)
     */
    protected $name;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity = "Elektra\SeedBundle\Entity\Auditing\Audit", fetch="EXTRA_LAZY", cascade={"persist", "remove"})
     * @ORM\JoinTable(name = "requestStatuses_audits",
     *      joinColumns = {@ORM\JoinColumn(name = "requestStatusId", referencedColumnName = "requestStatusId")},
     *      inverseJoinColumns = {@ORM\JoinColumn(name = "auditId", referencedColumnName = "auditId", unique = true)}
     */
    protected $audits;

    public function __construct()
    {
        $this->audits = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->requestStatusId;
    }

    /**
     * @return int
     */
    public function getRequestStatusId()
    {
        return $this->requestStatusId;
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
     * @param ArrayCollection
     */
    public function setAudits($audits)
    {
        $this->audits = $audits;
    }

    /**
     * @return ArrayCollection
     */
    public function getAudits()
    {
        return $this->audits;
    }
}