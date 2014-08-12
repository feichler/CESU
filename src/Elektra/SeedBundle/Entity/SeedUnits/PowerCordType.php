<?php

namespace Elektra\SeedBundle\Entity\SeedUnits;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Elektra\SeedBundle\Entity\Auditing\Audit;
use Elektra\SeedBundle\Entity\AuditableInterface;

/**
 * Class PowerType
 *
 * @package Elektra\SeedBundle\Entity\SeedUnits
 *
 * @ORM\Entity(repositoryClass="Elektra\SeedBundle\Repositories\SeedUnits\PowerCordTypeRepository")
 * @ORM\Table(name="powerCordTypes")
 */
class PowerCordType implements AuditableInterface
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
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
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity = "Elektra\SeedBundle\Entity\Auditing\Audit", fetch="EXTRA_LAZY", cascade={"persist", "remove"})
     * @ORM\OrderBy({"timestamp" = "DESC"})
     * @ORM\JoinTable(name = "powerCordTypes_audits",
     *      joinColumns = {@ORM\JoinColumn(name = "powerCordTypeId", referencedColumnName = "powerCordTypeId")},
     *      inverseJoinColumns = {@ORM\JoinColumn(name = "auditId", referencedColumnName = "auditId", unique = true)}
     * )
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
        return $this->powerCordTypeId;
    }

    /**
     * @return int
     */
    public function getPowerCordTypeId()
    {
        return $this->powerCordTypeId;
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

    /**
     * @return Audit
     */
    public function getCreationAudit()
    {
        return $this->getAudits()->slice(0, 1);
    }

    /**
     * @return Audit
     */
    public function getLastModifiedAudit()
    {
        $audits = $this->getAudits();
        return $audits->slice($audits->count()-1, 1);
    }
}