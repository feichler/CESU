<?php

namespace Elektra\SeedBundle\Entity\SeedUnits;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Elektra\SeedBundle\Repository\SeedUnits\StatusShippingRepository")
 * @ORM\Table(name="statuses_shipping")
 *
 * @ORM\HasLifecycleCallbacks()
 *
 * Unique:
 *      already defined by parent
 */
class StatusShipping extends Status
{

    // TODO remove old code after verification
    //    const AVAILABLE = "available";
    //    const RESERVED = "reserved";
    //    const EXCEPTION = "exception";
    //    const SHIPPED = "shipped";
    //    const IN_TRANSIT = "inTransit";
    //    const DELIVERED = "delivered";
    //    const ACKNOWLEDGE_ATTEMPT = "acknowledgeAttempt";
    //    const AA1SENT = "aa1sent";
    //    const AA3SENT = "aa3sent";
    //    const AA2SENT = "aa2sent";
    //    const ESCALATION = "escalation";
    //    const DELIVERY_VERIFIED = "deliveryVerified";
    //
    //    /**
    //     * @var array
    //     */
    //    static $ALL_INTERNAL_NAMES = array(
    //        StatusShipping::AVAILABLE,
    //        StatusShipping::RESERVED,
    //        StatusShipping::EXCEPTION,
    //        StatusShipping::IN_TRANSIT,
    //        StatusShipping::SHIPPED,
    //        StatusShipping::DELIVERED,
    //        StatusShipping::ACKNOWLEDGE_ATTEMPT,
    //        StatusShipping::AA1SENT,
    //        StatusShipping::AA2SENT,
    //        StatusShipping::AA3SENT,
    //        StatusShipping::ESCALATION,
    //        StatusShipping::DELIVERY_VERIFIED
    //    );
    //
    //    /**
    //     * @var array
    //     */
    //    static $ALLOWED_TO = array(
    //        StatusShipping::AVAILABLE           => array(),
    //        StatusShipping::RESERVED            => array(StatusShipping::AVAILABLE),
    //        StatusShipping::SHIPPED             => array(StatusShipping::RESERVED),
    //        StatusShipping::EXCEPTION           => array(StatusShipping::IN_TRANSIT),
    //        StatusShipping::IN_TRANSIT          => array(StatusShipping::SHIPPED, StatusShipping::EXCEPTION),
    //        StatusShipping::DELIVERED           => array(StatusShipping::IN_TRANSIT, StatusShipping::EXCEPTION),
    //        StatusShipping::ACKNOWLEDGE_ATTEMPT => array(StatusShipping::DELIVERED),
    //        StatusShipping::AA1SENT             => array(StatusShipping::ACKNOWLEDGE_ATTEMPT),
    //        StatusShipping::AA2SENT             => array(StatusShipping::AA1SENT),
    //        StatusShipping::AA3SENT             => array(StatusShipping::AA2SENT),
    //        StatusShipping::ESCALATION          => array(
    //            StatusShipping::DELIVERED,
    //            StatusShipping::ACKNOWLEDGE_ATTEMPT,
    //            StatusShipping::AA1SENT,
    //            StatusShipping::AA2SENT,
    //            StatusShipping::AA3SENT
    //        ),
    //        StatusShipping::DELIVERY_VERIFIED   => array(
    //            StatusShipping::DELIVERED,
    //            StatusShipping::ACKNOWLEDGE_ATTEMPT,
    //            StatusShipping::AA1SENT,
    //            StatusShipping::AA2SENT,
    //            StatusShipping::AA3SENT,
    //            StatusShipping::ESCALATION
    //        )
    //    );
    //
    //    /**
    //     * @var array
    //     */
    //    static $ALLOWED_FROM = array(
    //        StatusShipping::AVAILABLE           => array(StatusShipping::RESERVED),
    //        StatusShipping::RESERVED            => array(StatusShipping::SHIPPED),
    //        StatusShipping::SHIPPED             => array(StatusShipping::IN_TRANSIT),
    //        StatusShipping::EXCEPTION           => array(StatusShipping::IN_TRANSIT, StatusShipping::DELIVERED),
    //        StatusShipping::IN_TRANSIT          => array(StatusShipping::EXCEPTION, StatusShipping::DELIVERED),
    //        StatusShipping::DELIVERED           => array(
    //            StatusShipping::ACKNOWLEDGE_ATTEMPT,
    //            StatusShipping::ESCALATION,
    //            StatusShipping::DELIVERY_VERIFIED
    //        ),
    //        StatusShipping::ACKNOWLEDGE_ATTEMPT => array(
    //            StatusShipping::DELIVERY_VERIFIED,
    //            StatusShipping::ESCALATION,
    //            StatusShipping::AA1SENT
    //        ),
    //        StatusShipping::AA1SENT             => array(
    //            StatusShipping::DELIVERY_VERIFIED,
    //            StatusShipping::ESCALATION,
    //            StatusShipping::AA2SENT
    //        ),
    //        StatusShipping::AA2SENT             => array(
    //            StatusShipping::DELIVERY_VERIFIED,
    //            StatusShipping::ESCALATION,
    //            StatusShipping::AA3SENT
    //        ),
    //        StatusShipping::AA3SENT             => array(StatusShipping::DELIVERY_VERIFIED, StatusShipping::ESCALATION),
    //        StatusShipping::ESCALATION          => array(StatusShipping::DELIVERY_VERIFIED),
    //        StatusShipping::DELIVERY_VERIFIED   => array()
    //    );

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

    // none

    /*************************************************************************
     * EntityInterface
     *************************************************************************/

    // none

    /*************************************************************************
     * Lifecycle callbacks
     *************************************************************************/

    // none

}