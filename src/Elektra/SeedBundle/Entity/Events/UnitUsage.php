<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Entity\Events;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Elektra\CrudBundle\Entity\EntityInterface as CrudInterface;
use Elektra\SeedBundle\Auditing\Helper;
use Elektra\SeedBundle\Entity\Auditing\Audit;
use Elektra\SeedBundle\Entity\AuditableInterface;

/**
 * Class UnitUsage
 *
 * @package Elektra\SeedBundle\Entity\Events
 *
 * @version 0.1-dev
 *
 * @ORM\Entity(repositoryClass="Elektra\SeedBundle\Repository\Events\UnitUsageRepository")
 * @ORM\Table(name="usageStatuses")
 * @ORM\HasLifecycleCallbacks
 */
class UnitUsage implements AuditableInterface, CrudInterface
{
    const USAGE_IDLE = "idle";

    const LOCATION_SCOPE_PARTNER = 'P';
    const LOCATION_SCOPE_CUSTOMER = 'C';

    const LOCATION_CONSTRAINT_REQUIRED = 'R';
    const LOCATION_CONSTRAINT_OPTIONAL = 'O';
    const LOCATION_CONSTRAINT_HIDDEN = 'H';

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $usageStatusId;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50, unique=true)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=3, unique=true)
     */
    protected $abbreviation;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50)
     */
    protected $internalName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=1, nullable=false)
     */
    protected $locationConstraint;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=1, nullable=false)
     */
    protected $locationScope;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity = "Elektra\SeedBundle\Entity\Auditing\Audit", fetch="EXTRA_LAZY", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"timestamp" = "DESC"})
     * @ORM\JoinTable(name = "usageStatuses_audits",
     *      joinColumns = {@ORM\JoinColumn(name = "usageStatusId", referencedColumnName = "usageStatusId")},
     *      inverseJoinColumns = {@ORM\JoinColumn(name = "auditId", referencedColumnName = "auditId", unique = true, onDelete="CASCADE")}
     * )
     */
    protected $audits;

    /**
     *
     */
    public function __construct()
    {

        $this->audits = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {

        return $this->usageStatusId;
    }

    /**
     * @return int
     */
    public function getUsageStatusId()
    {

        return $this->usageStatusId;
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
     * {@inheritdoc}
     */
    public function setAudits($audits)
    {

        $this->audits = $audits;
    }

    /**
     * {@inheritdoc}
     */
    public function getAudits()
    {

        return $this->audits;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreationAudit()
    {
        return Helper::getFirstAudit($this->getAudits());

    }

    /**
     * {@inheritdoc}
     */
    public function getLastModifiedAudit()
    {
        return Helper::getLastAudit($this->getAudits());

    }

    /**
     * {@inheritdoc}
     */
    public function getTitle()
    {

        return $this->getAbbreviation() . " (" . $this->getName() . ")";
    }

    /**
     * @param string $abbreviation
     */
    public function setAbbreviation($abbreviation)
    {

        $this->abbreviation = $abbreviation;
    }

    /**
     * @return string
     */
    public function getAbbreviation()
    {

        return $this->abbreviation;
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

    /**
     * @param string $locationConstraint
     * @throws \OutOfBoundsException
     */
    public function setLocationConstraint($locationConstraint)
    {
        if (!in_array($locationConstraint, array(UnitUsage::LOCATION_CONSTRAINT_HIDDEN, UnitUsage::LOCATION_CONSTRAINT_OPTIONAL, UnitUsage::LOCATION_CONSTRAINT_REQUIRED)))
            throw new \OutOfBoundsException("Unknown location constraint value: " . $locationConstraint);

        $this->locationConstraint = $locationConstraint;
    }

    /**
     * @return string
     */
    public function getLocationConstraint()
    {
        return $this->locationConstraint;
    }

    /**
     * @param $locationScope
     * @throws \OutOfBoundsException
     */
    public function setLocationScope($locationScope)
    {
        if (!in_array($locationScope, array(UnitUsage::LOCATION_SCOPE_CUSTOMER, UnitUsage::LOCATION_SCOPE_PARTNER)))
            throw new \OutOfBoundsException("Unknown location scope value: " . $locationScope);

        $this->locationScope = $locationScope;
    }

    /**
     * @return string
     */
    public function getLocationScope()
    {
        return $this->locationScope;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        if ($this->getInternalName() == null)
        {
            // TODO better way for unique identifier?
            $this->setInternalName(time() . rand());
        }
    }
}