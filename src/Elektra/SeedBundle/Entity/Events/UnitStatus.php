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
 * Class UnitStatus
 *
 * @package Elektra\SeedBundle\Entity\Events
 *
 * @version 0.1-dev
 *
 * @ORM\Entity(repositoryClass="Elektra\SeedBundle\Repository\Events\UnitStatusRepository")
 * @ORM\Table(name="unitStatuses")
 */
class UnitStatus implements AuditableInterface, CrudInterface
{

    const AVAILABLE = "available";

    const RESERVED = "reserved";

    const EXCEPTION = "exception";

    const SHIPPED = "shipped";

    const IN_TRANSIT = "inTransit";

    const DELIVERED = "delivered";

    const ACKNOWLEDGE_ATTEMPT = "acknowledgeAttempt";

    const AA1SENT = "aa1sent";

    const AA3SENT = "aa3sent";

    const AA2SENT = "aa2sent";

    const ESCALATION = "escalation";

    const DELIVERY_VERIFIED = "deliveryVerified";

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $unitStatusId;

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
     * @ORM\Column(type="string", length=25, unique=true)
     */
    protected $internalName;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity = "Elektra\SeedBundle\Entity\Auditing\Audit", fetch="EXTRA_LAZY", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"timestamp" = "DESC"})
     * @ORM\JoinTable(name = "unitStatuses_audits",
     *      joinColumns = {@ORM\JoinColumn(name = "unitStatusId", referencedColumnName = "unitStatusId")},
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

        return $this->unitStatusId;
    }

    /**
     * @return int
     */
    public function getUnitStatusId()
    {

        return $this->unitStatusId;
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
}