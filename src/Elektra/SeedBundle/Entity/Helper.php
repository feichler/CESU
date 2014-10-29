<?php

namespace Elektra\SeedBundle\Entity;

abstract class Helper
{

    /*************************************************************************
     * Constants for ContactInfoType
     *************************************************************************/

    const CONTACT_INFO_TYPE_PHONE     = "phone";
    const CONTACT_INFO_TYPE_FAX       = "fax";
    const CONTACT_INFO_TYPE_MOBILE    = "mobile";
    const CONTACT_INFO_TYPE_EMAIL     = "email";
    const CONTACT_INFO_TYPE_MESSENGER = "messenger";
    const CONTACT_INFO_TYPE_OTHER     = "other";

    /*************************************************************************
     * Constants for GenericLocation
     *************************************************************************/

    const GENERIC_LOCATION_IN_TRANSIT = 'inTransit';
    const GENERIC_LOCATION_UNKNOWN    = 'unknown';

    /*************************************************************************
     * Constants for EventType
     *************************************************************************/

    const EVENT_TYPE_GENERIC       = 'generic';
    const EVENT_TYPE_SHIPPING      = "shipping";
    const EVENT_TYPE_PARTNER       = "partner";
    const EVENT_TYPE_SALES         = "sales";
    const EVENT_TYPE_COMMUNICATION = "communication";

    /*************************************************************************
     * Constants for StatusShipping
     *************************************************************************/

    const STATUS_SHIPPING_AVAILABLE           = 'available';
    const STATUS_SHIPPING_RESERVED            = "reserved";
    const STATUS_SHIPPING_EXCEPTION           = "exception";
    const STATUS_SHIPPING_SHIPPED             = "shipped";
    const STATUS_SHIPPING_IN_TRANSIT          = "inTransit";
    const STATUS_SHIPPING_DELIVERED           = "delivered";
    const STATUS_SHIPPING_ACKNOWLEDGE_ATTEMPT = "acknowledgeAttempt";
    const STATUS_SHIPPING_AA1SENT             = "aa1sent";
    const STATUS_SHIPPING_AA3SENT             = "aa3sent";
    const STATUS_SHIPPING_AA2SENT             = "aa2sent";
    const STATUS_SHIPPING_ESCALATION          = "escalation";
    const STATUS_SHIPPING_DELIVERY_VERIFIED   = "deliveryVerified";

    /*************************************************************************
     * Constants for StatusUsage
     *************************************************************************/

    const STATUS_USAGE_IDLE = 'idle';

    const STATUS_USAGE_LOCATION_SCOPE_PARTNER  = 'P';
    const STATUS_USAGE_LOCATION_SCOPE_CUSTOMER = 'C';

    const STATUS_USAGE_LOCATION_CONSTRAINT_REQUIRED = 'R';
    const STATUS_USAGE_LOCATION_CONSTRAINT_OPTIONAL = 'O';
    const STATUS_USAGE_LOCATION_CONSTRAINT_HIDDEN   = 'H';

    /**
     * Private constructor to prevent any instances
     */
    private function __construct()
    {
    }

    /**
     * Get all known names for ContactInfoType
     *
     * @return array
     */
    public static function getContactInfoTypNames()
    {

        static $all = array(
            self::CONTACT_INFO_TYPE_PHONE,
            self::CONTACT_INFO_TYPE_FAX,
            self::CONTACT_INFO_TYPE_MOBILE,
            self::CONTACT_INFO_TYPE_EMAIL,
            self::CONTACT_INFO_TYPE_MESSENGER,
            self::CONTACT_INFO_TYPE_OTHER,
        );

        return $all;
    }

    /**
     * Get all known names for GenericLocation
     *
     * @return array
     */
    public static function getGenericLocationNames()
    {

        static $all = array(
            self::GENERIC_LOCATION_IN_TRANSIT,
            self::GENERIC_LOCATION_UNKNOWN,
        );

        return $all;
    }

    /**
     * Get all known names for EventType
     *
     * @return array
     */
    public static function getEventTypeNames()
    {

        static $all = array(
            self::EVENT_TYPE_GENERIC,
            self::EVENT_TYPE_SHIPPING,
            self::EVENT_TYPE_PARTNER,
            self::EVENT_TYPE_SALES,
            self::EVENT_TYPE_COMMUNICATION,
        );

        return $all;
    }

    /**
     * Get all known names for StatusShipping
     *
     * @return array
     */
    public static function getStatusShippingNames()
    {

        static $all = array(
            self::STATUS_SHIPPING_AVAILABLE,
            self::STATUS_SHIPPING_RESERVED,
            self::STATUS_SHIPPING_EXCEPTION,
            self::STATUS_SHIPPING_SHIPPED,
            self::STATUS_SHIPPING_IN_TRANSIT,
            self::STATUS_SHIPPING_DELIVERED,
            self::STATUS_SHIPPING_ACKNOWLEDGE_ATTEMPT,
            self::STATUS_SHIPPING_AA1SENT,
            self::STATUS_SHIPPING_AA2SENT,
            self::STATUS_SHIPPING_AA3SENT,
            self::STATUS_SHIPPING_EXCEPTION,
            self::STATUS_SHIPPING_DELIVERY_VERIFIED,
        );

        return $all;
    }

    /**
     * Gets all allowed StatusShipping transitions as multidimensional array:
     * Key: new Status
     * Values: array of allowed statuses to get to the new Status
     *
     * @return array
     */
    public static function getStatusShippingAllowedTo()
    {

        static $all = array(
            self::STATUS_SHIPPING_AVAILABLE           => array(),
            self::STATUS_SHIPPING_RESERVED            => array(
                self::STATUS_SHIPPING_AVAILABLE,
            ),
            self::STATUS_SHIPPING_SHIPPED             => array(
                self::STATUS_SHIPPING_RESERVED,
            ),
            self::STATUS_SHIPPING_EXCEPTION           => array(
                self::STATUS_SHIPPING_IN_TRANSIT,
            ),
            self::STATUS_SHIPPING_IN_TRANSIT          => array(
                self::STATUS_SHIPPING_SHIPPED,
                self::STATUS_SHIPPING_EXCEPTION,
            ),
            self::STATUS_SHIPPING_DELIVERED           => array(
                self::STATUS_SHIPPING_IN_TRANSIT,
                self::STATUS_SHIPPING_EXCEPTION,
            ),
            self::STATUS_SHIPPING_ACKNOWLEDGE_ATTEMPT => array(
                self::STATUS_SHIPPING_DELIVERED,
            ),
            self::STATUS_SHIPPING_AA1SENT             => array(
                self::STATUS_SHIPPING_ACKNOWLEDGE_ATTEMPT,
            ),
            self::STATUS_SHIPPING_AA2SENT             => array(
                self::STATUS_SHIPPING_AA1SENT,
            ),
            self::STATUS_SHIPPING_AA3SENT             => array(
                self::STATUS_SHIPPING_AA2SENT,
            ),
            self::STATUS_SHIPPING_ESCALATION          => array(
                self::STATUS_SHIPPING_DELIVERED,
                self::STATUS_SHIPPING_ACKNOWLEDGE_ATTEMPT,
                self::STATUS_SHIPPING_AA1SENT,
                self::STATUS_SHIPPING_AA2SENT,
                self::STATUS_SHIPPING_AA3SENT,
            ),
            self::STATUS_SHIPPING_DELIVERY_VERIFIED   => array(
                self::STATUS_SHIPPING_DELIVERED,
                self::STATUS_SHIPPING_ACKNOWLEDGE_ATTEMPT,
                self::STATUS_SHIPPING_AA1SENT,
                self::STATUS_SHIPPING_AA2SENT,
                self::STATUS_SHIPPING_AA3SENT,
                self::STATUS_SHIPPING_ESCALATION,
            ),
        );

        return $all;
    }

    /**
     * Gets all allowed StatusShipping transitions as multidimensional array:
     * Key: actual Status
     * Values: array of allowed statuses to get to
     *
     * @return array
     */
    public static function getStatusShippingAllowedFrom()
    {

        static $all = array(
            self::STATUS_SHIPPING_AVAILABLE           => array(
                self::STATUS_SHIPPING_RESERVED,
            ),
            self::STATUS_SHIPPING_RESERVED            => array(
                self::STATUS_SHIPPING_SHIPPED,
            ),
            self::STATUS_SHIPPING_SHIPPED             => array(
                self::STATUS_SHIPPING_IN_TRANSIT,
            ),
            self::STATUS_SHIPPING_EXCEPTION           => array(
                self::STATUS_SHIPPING_IN_TRANSIT,
                self::STATUS_SHIPPING_DELIVERED,
            ),
            self::STATUS_SHIPPING_IN_TRANSIT          => array(
                self::STATUS_SHIPPING_EXCEPTION,
                self::STATUS_SHIPPING_DELIVERED,
            ),
            self::STATUS_SHIPPING_DELIVERED           => array(
                self::STATUS_SHIPPING_ACKNOWLEDGE_ATTEMPT,
                self::STATUS_SHIPPING_ESCALATION,
                self::STATUS_SHIPPING_DELIVERY_VERIFIED,
            ),
            self::STATUS_SHIPPING_ACKNOWLEDGE_ATTEMPT => array(
                self::STATUS_SHIPPING_DELIVERY_VERIFIED,
                self::STATUS_SHIPPING_ESCALATION,
                self::STATUS_SHIPPING_AA1SENT,
            ),
            self::STATUS_SHIPPING_AA1SENT             => array(
                self::STATUS_SHIPPING_DELIVERY_VERIFIED,
                self::STATUS_SHIPPING_ESCALATION,
                self::STATUS_SHIPPING_AA2SENT,
            ),
            self::STATUS_SHIPPING_AA2SENT             => array(
                self::STATUS_SHIPPING_DELIVERY_VERIFIED,
                self::STATUS_SHIPPING_ESCALATION,
                self::STATUS_SHIPPING_AA3SENT,
            ),
            self::STATUS_SHIPPING_AA3SENT             => array(
                self::STATUS_SHIPPING_DELIVERY_VERIFIED,
                self::STATUS_SHIPPING_ESCALATION,
            ),
            self::STATUS_SHIPPING_ESCALATION          => array(
                self::STATUS_SHIPPING_DELIVERY_VERIFIED,
            ),
            self::STATUS_SHIPPING_DELIVERY_VERIFIED   => array(),
        );

        return $all;
    }

    /**
     * Get all known location scopes for StatusUsage
     *
     * @return array
     */
    public static function getStatusUsageLocationScopes()
    {

        static $all = array(
            self::STATUS_USAGE_LOCATION_SCOPE_PARTNER,
            self::STATUS_USAGE_LOCATION_SCOPE_CUSTOMER,
        );

        return $all;
    }

    /**
     * Get all known location constraints for StatusUsage
     *
     * @return array
     */
    public static function getStatusUsageLocationConstraints()
    {

        static $all = array(
            self::STATUS_USAGE_LOCATION_CONSTRAINT_REQUIRED,
            self::STATUS_USAGE_LOCATION_CONSTRAINT_OPTIONAL,
            self::STATUS_USAGE_LOCATION_CONSTRAINT_HIDDEN,
        );

        return $all;
    }
}