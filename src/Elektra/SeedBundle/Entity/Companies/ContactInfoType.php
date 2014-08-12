<?php

namespace Elektra\SeedBundle\Entity\Companies;

use Doctrine\ORM\Mapping as ORM;
use Elektra\SeedBundle\Entity\Auditing\Audit;
use Elektra\SeedBundle\Entity\AuditableInterface;

/**
 * Class ContactInfoType
 *
 * @package Elektra\SeedBundle\Entity\Companies
 *
 * @ORM\Entity
 * @ORM\Table(name="contactInfoTypes")
 */
class ContactInfoType implements AuditableInterface
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $contactInfoTypeId;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50, unique=true)
     */
    protected $name;

    /**
     * @var Audit
     *
     * @ORM\OneToOne(targetEntity="Elektra\SeedBundle\Entity\Auditing\Audit", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="auditId", referencedColumnName="auditId")
     */
    protected $audit;

    public function __construct()
    {
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->contactInfoTypeId;
    }

    /**
     * @return int
     */
    public function getContactInfoTypeId()
    {
        return $this->contactInfoTypeId;
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
     * @param \Elektra\SeedBundle\Entity\Auditing\Audit $audit
     */
    public function setAudit($audit)
    {
        $this->audit = $audit;
    }

    /**
     * @return \Elektra\SeedBundle\Entity\Auditing\Audit
     */
    public function getAudit()
    {
        return $this->audit;
    }
}