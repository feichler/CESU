<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Entity\SeedUnits;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Elektra\CrudBundle\Entity\EntityInterface as CrudInterface;
use Elektra\SeedBundle\Auditing\Helper;
use Elektra\SeedBundle\Entity\Auditing\Audit;
use Elektra\SeedBundle\Entity\AuditableInterface;

/**
 * Class ShippingStatus
 *
 * @package Elektra\SeedBundle\Entity\SeedUnits
 *
 * @version 0.1-dev
 *
 * @ORM\Entity(repositoryClass="Elektra\SeedBundle\Repository\SeedUnits\ShippingStatusRepository")
 * @ORM\Table(name="shippingStatuses")
 */
class ShippingStatus implements AuditableInterface, CrudInterface
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
        ShippingStatus::AVAILABLE,
        ShippingStatus::RESERVED,
        ShippingStatus::EXCEPTION,
        ShippingStatus::IN_TRANSIT,
        ShippingStatus::SHIPPED,
        ShippingStatus::DELIVERED,
        ShippingStatus::ACKNOWLEDGE_ATTEMPT,
        ShippingStatus::AA1SENT,
        ShippingStatus::AA2SENT,
        ShippingStatus::AA3SENT,
        ShippingStatus::ESCALATION,
        ShippingStatus::DELIVERY_VERIFIED
    );

    /**
     * @var array
     */
    static $ALLOWED_TO = array(
        ShippingStatus::AVAILABLE => array(),
        ShippingStatus::RESERVED => array(ShippingStatus::AVAILABLE),
        ShippingStatus::SHIPPED => array(ShippingStatus::RESERVED),
        ShippingStatus::EXCEPTION => array(ShippingStatus::IN_TRANSIT),
        ShippingStatus::IN_TRANSIT => array(ShippingStatus::SHIPPED, ShippingStatus::EXCEPTION),
        ShippingStatus::DELIVERED => array(ShippingStatus::IN_TRANSIT, ShippingStatus::EXCEPTION),
        ShippingStatus::ACKNOWLEDGE_ATTEMPT => array(ShippingStatus::DELIVERED),
        ShippingStatus::AA1SENT => array(ShippingStatus::ACKNOWLEDGE_ATTEMPT),
        ShippingStatus::AA2SENT => array(ShippingStatus::AA1SENT),
        ShippingStatus::AA3SENT => array(ShippingStatus::AA2SENT),
        ShippingStatus::ESCALATION => array(ShippingStatus::DELIVERED, ShippingStatus::ACKNOWLEDGE_ATTEMPT, ShippingStatus::AA1SENT, ShippingStatus::AA2SENT, ShippingStatus::AA3SENT),
        ShippingStatus::DELIVERY_VERIFIED => array(ShippingStatus::DELIVERED, ShippingStatus::ACKNOWLEDGE_ATTEMPT, ShippingStatus::AA1SENT, ShippingStatus::AA2SENT, ShippingStatus::AA3SENT, ShippingStatus::ESCALATION)
    );

    /**
     * @var array
     */
    static $ALLOWED_FROM = array(
        ShippingStatus::AVAILABLE => array(ShippingStatus::RESERVED),
        ShippingStatus::RESERVED => array(ShippingStatus::SHIPPED),
        ShippingStatus::SHIPPED => array(ShippingStatus::IN_TRANSIT),
        ShippingStatus::EXCEPTION => array(ShippingStatus::IN_TRANSIT, ShippingStatus::DELIVERED),
        ShippingStatus::IN_TRANSIT => array(ShippingStatus::EXCEPTION, ShippingStatus::DELIVERED),
        ShippingStatus::DELIVERED => array(ShippingStatus::ACKNOWLEDGE_ATTEMPT, ShippingStatus::ESCALATION, ShippingStatus::DELIVERY_VERIFIED),
        ShippingStatus::ACKNOWLEDGE_ATTEMPT => array(ShippingStatus::DELIVERY_VERIFIED, ShippingStatus::ESCALATION, ShippingStatus::AA1SENT),
        ShippingStatus::AA1SENT => array(ShippingStatus::DELIVERY_VERIFIED, ShippingStatus::ESCALATION, ShippingStatus::AA2SENT),
        ShippingStatus::AA2SENT => array(ShippingStatus::DELIVERY_VERIFIED, ShippingStatus::ESCALATION, ShippingStatus::AA3SENT),
        ShippingStatus::AA3SENT => array(ShippingStatus::DELIVERY_VERIFIED, ShippingStatus::ESCALATION),
        ShippingStatus::ESCALATION => array(ShippingStatus::DELIVERY_VERIFIED),
        ShippingStatus::DELIVERY_VERIFIED => array()
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