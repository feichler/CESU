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
 * @ORM\Table(name="shippingStatuses")
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
     * @var array
     */
    static $ALL_INTERNAL_NAMES = array(
        UnitStatus::AVAILABLE,
        UnitStatus::RESERVED,
        UnitStatus::EXCEPTION,
        UnitStatus::IN_TRANSIT,
        UnitStatus::SHIPPED,
        UnitStatus::DELIVERED,
        UnitStatus::ACKNOWLEDGE_ATTEMPT,
        UnitStatus::AA1SENT,
        UnitStatus::AA2SENT,
        UnitStatus::AA3SENT,
        UnitStatus::ESCALATION,
        UnitStatus::DELIVERY_VERIFIED
    );

    /**
     * @var array
     */
    static $ALLOWED_TO = array(
        UnitStatus::AVAILABLE => array(),
        UnitStatus::RESERVED => array(UnitStatus::AVAILABLE),
        UnitStatus::SHIPPED => array(UnitStatus::RESERVED),
        UnitStatus::EXCEPTION => array(UnitStatus::IN_TRANSIT),
        UnitStatus::IN_TRANSIT => array(UnitStatus::SHIPPED, UnitStatus::EXCEPTION),
        UnitStatus::DELIVERED => array(UnitStatus::IN_TRANSIT, UnitStatus::EXCEPTION),
        UnitStatus::ACKNOWLEDGE_ATTEMPT => array(UnitStatus::DELIVERED),
        UnitStatus::AA1SENT => array(UnitStatus::ACKNOWLEDGE_ATTEMPT),
        UnitStatus::AA2SENT => array(UnitStatus::AA1SENT),
        UnitStatus::AA3SENT => array(UnitStatus::AA2SENT),
        UnitStatus::ESCALATION => array(UnitStatus::DELIVERED, UnitStatus::ACKNOWLEDGE_ATTEMPT, UnitStatus::AA1SENT, UnitStatus::AA2SENT, UnitStatus::AA3SENT),
        UnitStatus::DELIVERY_VERIFIED => array(UnitStatus::DELIVERED, UnitStatus::ACKNOWLEDGE_ATTEMPT, UnitStatus::AA1SENT, UnitStatus::AA2SENT, UnitStatus::AA3SENT, UnitStatus::ESCALATION)
    );

    /**
     * @var array
     */
    static $ALLOWED_FROM = array(
        UnitStatus::AVAILABLE => array(UnitStatus::RESERVED),
        UnitStatus::RESERVED => array(UnitStatus::SHIPPED),
        UnitStatus::SHIPPED => array(UnitStatus::IN_TRANSIT),
        UnitStatus::EXCEPTION => array(UnitStatus::IN_TRANSIT, UnitStatus::DELIVERED),
        UnitStatus::IN_TRANSIT => array(UnitStatus::EXCEPTION, UnitStatus::DELIVERED),
        UnitStatus::DELIVERED => array(UnitStatus::ACKNOWLEDGE_ATTEMPT, UnitStatus::ESCALATION, UnitStatus::DELIVERY_VERIFIED),
        UnitStatus::ACKNOWLEDGE_ATTEMPT => array(UnitStatus::DELIVERY_VERIFIED, UnitStatus::ESCALATION, UnitStatus::AA1SENT),
        UnitStatus::AA1SENT => array(UnitStatus::DELIVERY_VERIFIED, UnitStatus::ESCALATION, UnitStatus::AA2SENT),
        UnitStatus::AA2SENT => array(UnitStatus::DELIVERY_VERIFIED, UnitStatus::ESCALATION, UnitStatus::AA3SENT),
        UnitStatus::AA3SENT => array(UnitStatus::DELIVERY_VERIFIED, UnitStatus::ESCALATION),
        UnitStatus::ESCALATION => array(UnitStatus::DELIVERY_VERIFIED),
        UnitStatus::DELIVERY_VERIFIED => array()
    );

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $shippingStatusId;

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
     * @ORM\JoinTable(name = "shippingStatuses_audits",
     *      joinColumns = {@ORM\JoinColumn(name = "shippingStatusId", referencedColumnName = "shippingStatusId")},
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

        return $this->shippingStatusId;
    }

    /**
     * @return int
     */
    public function getShippingStatusId()
    {

        return $this->shippingStatusId;
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