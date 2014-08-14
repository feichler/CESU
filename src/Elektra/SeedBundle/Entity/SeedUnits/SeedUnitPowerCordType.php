<?php

namespace Elektra\SeedBundle\Entity\SeedUnits;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Elektra\SeedBundle\Entity\Auditing\Audit;
use Elektra\SeedBundle\Entity\AuditableInterface;
use Elektra\SeedBundle\Entity\CRUDEntityInterface;

/**
 * Class SeedUnitPowerTypes
 *
 * @package Elektra\SeedBundle\Entity\SeedUnits
 *
 * @ORM\Entity(repositoryClass="Elektra\SeedBundle\Repositories\SeedUnits\SeedUnitPowerCordTypeRepository")
 * @ORM\Table(name="powerCordTypes")
 */
class SeedUnitPowerCordType implements AuditableInterface, CRUDEntityInterface
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
     * @ORM\ManyToMany(targetEntity = "Elektra\SeedBundle\Entity\Auditing\Audit", fetch="EXTRA_LAZY", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"timestamp" = "DESC"})
     * @ORM\JoinTable(name = "powerCordTypes_audits",
     *      joinColumns = {@ORM\JoinColumn(name = "powerCordTypeId", referencedColumnName = "powerCordTypeId")},
     *      inverseJoinColumns = {@ORM\JoinColumn(name = "auditId", referencedColumnName = "auditId", unique = true, onDelete="CASCADE")}
     * )
     */
    protected $audits;

    public function __construct()
    {

        $this->audits = new ArrayCollection();
    }

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
    public function getTitle()
    {

        return $this->getName();
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

        return $this->getAudits()->slice(0, 1)[0];
    }

    /**
     * @return Audit
     */
    public function getLastModifiedAudit()
    {

        $audits = $this->getAudits();

        return $audits->count() > 1 ? $audits->slice($audits->count() - 1, 1)[0] : null;
    }
}