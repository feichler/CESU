<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 11.09.14
 * Time: 16:39
 */

namespace Elektra\SeedBundle\Controller;


use Doctrine\Common\Persistence\ObjectManager;
use Elektra\SeedBundle\Entity\Companies\CompanyLocation;
use Elektra\SeedBundle\Entity\Companies\CompanyPerson;
use Elektra\SeedBundle\Entity\Companies\GenericLocation;
use Elektra\SeedBundle\Entity\Companies\Location;
use Elektra\SeedBundle\Entity\Companies\WarehouseLocation;
use Elektra\SeedBundle\Entity\Events\ActivityEvent;
use Elektra\SeedBundle\Entity\Events\Event;
use Elektra\SeedBundle\Entity\Events\EventType;
use Elektra\SeedBundle\Entity\Events\InTransitEvent;
use Elektra\SeedBundle\Entity\SeedUnits\SalesStatus;
use Elektra\SeedBundle\Entity\SeedUnits\ShippingStatus;
use Elektra\SeedBundle\Entity\SeedUnits\UsageStatus;
use Elektra\SeedBundle\Repository\Events\EventTypeRepository;
use Elektra\SeedBundle\Repository\SeedUnits\ShippingStatusRepository;
use Elektra\SeedBundle\Repository\SeedUnits\UsageStatusRepository;

class EventFactory {

    /**
     * @var ObjectManager $mgr
     */
    private $mgr;

    /**
     * @var ShippingStatusRepository $shippingStatusRepository
     */
    private $shippingStatusRepository;

    /**
     * @var EventTypeRepository $eventTypeRepository
     */
    private $eventTypeRepository;

    /**
     * @var UsageStatusRepository $usageStatusRepository
     */
    private $usageStatusRepository;

    const TIMESTAMP = 'timestamp';
    const TEXT = 'text';
    const LOCATION = 'location';
    const REQUEST_NUMBER = 'requestNumber';
    const PERSON = 'person';
    const IGNORE_MISSING = 'ignoreMissing';

    /**
     *
     */
    public function __construct(ObjectManager $mgr)
    {
        $this->mgr = $mgr;
        $this->shippingStatusRepository = $mgr->getRepository('ElektraSeedBundle:SeedUnits\ShippingStatus');
        $this->eventTypeRepository = $mgr->getRepository('ElektraSeedBundle:Events\EventType');
        $this->usageStatusRepository = $mgr->getRepository('ElektraSeedBundle:SeedUnits\UsageStatus');
    }

    public function createSalesEvent(SalesStatus $salesStatus, array $options)
    {
        $eventType = $this->eventTypeRepository->findByInternalName(EventType::PARTNER);

        $event = $this->_createEvent("Sales status changed to '" . $salesStatus->getName() . "'.",
            $eventType,
            null, null, $salesStatus, null, $options);

        return $event;
    }

    /**
     * @param UsageStatus $usage
     * @param array $options
     * @return Event
     */
    public function createUsageEvent(UsageStatus $usage, array $options)
    {
        $eventType = $this->eventTypeRepository->findByInternalName(EventType::PARTNER);

        $event = $this->_createEvent("Usage changed to '" . $usage->getName() . "'.",
            $eventType,
            null, $usage, null, null, $options);

        return $event;
    }

    public function createShippingEvent($status, array $options)
    {
        $event = null;
        switch($status)
        {
            case ShippingStatus::AVAILABLE:
                $event = $this->createAvailable($this->_getMandatoryOption(EventFactory::LOCATION, $options),
                    $options);
                break;
            case ShippingStatus::RESERVED:
                $event = $this->createReserved($this->_getMandatoryOption(EventFactory::LOCATION, $options),
                    $this->_getMandatoryOption(EventFactory::REQUEST_NUMBER, $options),
                    $options);
                break;

            case ShippingStatus::SHIPPED:
                $event = $this->createShipped($options);
                break;

            case ShippingStatus::IN_TRANSIT:
                $event = $this->createInTransit($options);
                break;

            case ShippingStatus::DELIVERED:
                $event = $this->createDelivered($this->_getMandatoryOption(EventFactory::LOCATION, $options),
                    $options);
                break;

            case ShippingStatus::EXCEPTION:
                $event = $this->createException($options);
                break;

            case ShippingStatus::ACKNOWLEDGE_ATTEMPT:
                $event = $this->createAcknowledgeAttempt($this->_getMandatoryOption(EventFactory::PERSON, $options),
                    $options);
                break;

            case ShippingStatus::AA1SENT:
                $event = $this->createAA1Sent($this->_getMandatoryOption(EventFactory::PERSON, $options), $options);
                break;

            case ShippingStatus::AA2SENT:
                $event = $this->createAA2Sent($this->_getMandatoryOption(EventFactory::PERSON, $options), $options);
                break;

            case ShippingStatus::AA3SENT:
                $event = $this->createAA3Sent($this->_getMandatoryOption(EventFactory::PERSON, $options), $options);
                break;

            case ShippingStatus::ESCALATION:
                $event = $this->createEscalation($this->_getMandatoryOption(EventFactory::PERSON, $options),
                    $options);
                break;

            case ShippingStatus::DELIVERY_VERIFIED:
                $event = $this->createDeliveryVerified($this->_getMandatoryOption(EventFactory::PERSON, $options),
                    $options);
                break;

            default:
                throw new \OutOfBoundsException("Invalid shipping status '" . $status . "'.");
        }

        return $event;
    }

    public function createAvailable(WarehouseLocation $location, array $options = array())
    {
        $event = $this->_createShippingEvent("Seed Unit available at warehouse.",
            $this->shippingStatusRepository->findByInternalName(ShippingStatus::AVAILABLE),
            $location,
            $options
        );

        return $event;
    }

    public function createReserved(WarehouseLocation $location, $requestNumber, array $options)
    {
        $event = $this->_createShippingEvent("Reserved for request " . $requestNumber,
            $this->shippingStatusRepository->findByInternalName(ShippingStatus::RESERVED),
            $location,
            $options
        );

        return $event;
    }

    public function createShipped(array $options)
    {
        $event = $this->_createShippingEvent('Unit has been shipped.',
            $this->shippingStatusRepository->findByInternalName(ShippingStatus::SHIPPED),
            $this->mgr
                ->getRepository('ElektraSeedBundle:Companies\GenericLocation')
                ->findByInternalName(GenericLocation::IN_TRANSIT),
            $options
        );

        return $event;
    }

    public function createInTransit(array $options)
    {
        $eventType = $this->eventTypeRepository->findByInternalName(EventType::SHIPPING);
        $shippingStatus = $this->shippingStatusRepository->findByInternalName(ShippingStatus::IN_TRANSIT);
        $location = $this->mgr
            ->getRepository('ElektraSeedBundle:Companies\GenericLocation')
            ->findByInternalName(GenericLocation::IN_TRANSIT);

        $event = new InTransitEvent();
        $this->_populateEvent($event, $eventType, 'Unit is being delivered.', $shippingStatus, null, null, $location, $options);

        return $event;
    }

    public function createException(array $options)
    {
        $event = $this->_createShippingEvent('Exception: Unit lost in transit.',
            $this->shippingStatusRepository->findByInternalName(ShippingStatus::EXCEPTION),
            $this->mgr
                ->getRepository('ElektraSeedBundle:Companies\GenericLocation')
                ->findByInternalName(GenericLocation::UNKNOWN),
            $options
        );

        return $event;
    }

    public function createDelivered(CompanyLocation $location = null, array $options)
    {
        $event = $this->_createShippingEvent('Unit arrived at target location.',
            $this->shippingStatusRepository->findByInternalName(ShippingStatus::DELIVERED),
            $location,
            $options
        );

        return $event;
    }

    public function createAcknowledgeAttempt(CompanyPerson $person = null, array $options)
    {
        $event = $this->_createActivityEvent("Acknowledge attempted - delivery couldn't be verified.",
            $this->shippingStatusRepository->findByInternalName(ShippingStatus::ACKNOWLEDGE_ATTEMPT),
            $person,
            $options
        );

        return $event;
    }

    public function createAA1Sent(CompanyPerson $person = null, array $options)
    {
        $event = $this->_createActivityEvent("Acknowledge attempted - delivery couldn't be verified.",
            $this->shippingStatusRepository->findByInternalName(ShippingStatus::AA1SENT),
            $person,
            $options
        );

        return $event;
    }

    public function createAA2Sent(CompanyPerson $person = null, array $options)
    {
        $event = $this->_createActivityEvent("Acknowledge attempted - delivery couldn't be verified.",
            $this->shippingStatusRepository->findByInternalName(ShippingStatus::AA2SENT),
            $person,
            $options
        );

        return $event;
    }

    public function createAA3Sent(CompanyPerson $person = null, array $options)
    {
        $event = $this->_createActivityEvent("Acknowledge attempted - delivery couldn't be verified.",
            $this->shippingStatusRepository->findByInternalName(ShippingStatus::AA3SENT),
            $person,
            $options
        );

        return $event;
    }

    public function createEscalation(CompanyPerson $person = null, array $options)
    {
        $event = $this->_createActivityEvent("Escalation: Delivery couldn't be verified.",
            $this->shippingStatusRepository->findByInternalName(ShippingStatus::ESCALATION),
            $person,
            $options
        );

        return $event;
    }

    public function createDeliveryVerified(CompanyPerson $person = null, array $options)
    {
        $event = $this->_createActivityEvent("Delivery verified.",
            $this->shippingStatusRepository->findByInternalName(ShippingStatus::DELIVERY_VERIFIED),
            $person,
            $options
        );

        // set usage to idle once a seed unit is delivered
        $idleUsage = $this->usageStatusRepository->findByInternalName(UsageStatus::USAGE_IDLE);
        $event->setUsageStatus($idleUsage);

        return $event;
    }

    private function _createEvent($title, EventType $eventType, ShippingStatus $shippingStatus = null, UsageStatus $usage = null, SalesStatus $salesStatus = null, Location $location = null, array $options)
    {
        $event = new Event();

        $this->_populateEvent($event, $eventType, $title, $shippingStatus, $usage, $salesStatus, $location, $options);
        return $event;
    }

    private function _createActivityEvent($title, ShippingStatus $shippingStatus = null, CompanyPerson $person = null, array $options)
    {
        $eventType = $this->eventTypeRepository->findByInternalName(EventType::SHIPPING);

        $event = new ActivityEvent();
        $this->_populateEvent($event, $eventType, $title, $shippingStatus, null, null, null, $options);
        $event->setPerson($person);

        return $event;
    }

    private function _createShippingEvent($title, ShippingStatus $shippingStatus = null, Location $location = null, array $options)
    {
        $eventType = $this->eventTypeRepository->findByInternalName(EventType::SHIPPING);

        $event = new Event();
        $this->_populateEvent($event, $eventType, $title, $shippingStatus, null, null, $location, $options);

        return $event;
    }

    private function _getMandatoryOption($name, array $options)
    {
        if (!array_key_exists($name, $options) and !(array_key_exists(EventFactory::IGNORE_MISSING, $options) and $options[EventFactory::IGNORE_MISSING]))
            throw new \OutOfBoundsException("Mandatory option '" . $name . "' missing.");

        return array_key_exists($name, $options) ? $options[$name] : null;
    }

    private function _populateEvent(Event $event, EventType $eventType, $title, ShippingStatus $shippingStatus = null, UsageStatus $usage = null, SalesStatus $salesStatus = null, Location $location = null, array $options)
    {
        $event->setText($title);
        $event->setEventType($eventType);
        $event->setUsageStatus($usage);
        $event->setSalesStatus($salesStatus);
        $event->setShippingStatus($shippingStatus);
        $event->setLocation($location);

        if (isset($options[EventFactory::TIMESTAMP]))
        {
            $event->setTimestamp($options[EventFactory::TIMESTAMP]);
        }

        if (isset($options[EventFactory::TEXT]))
        {
            $event->setComment($options[EventFactory::TEXT]);
        }
    }
}