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
use Elektra\SeedBundle\Entity\Events\PartnerEvent;
use Elektra\SeedBundle\Entity\Events\ShippingEvent;
use Elektra\SeedBundle\Entity\Events\UnitStatus;
use Elektra\SeedBundle\Entity\Events\UnitUsage;
use Elektra\SeedBundle\Entity\Requests\Request;
use Elektra\SeedBundle\Entity\SeedUnits\SeedUnit;
use Elektra\SeedBundle\Repository\Events\EventTypeRepository;
use Elektra\SeedBundle\Repository\Events\UnitStatusRepository;

class EventFactory {

    /**
     * @var ObjectManager $mgr
     */
    private $mgr;

    /**
     * @var UnitStatusRepository $unitStatusRepository
     */
    private $unitStatusRepository;

    /**
     * @var EventTypeRepository $eventTypeRepository
     */
    private $eventTypeRepository;

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
        $this->unitStatusRepository = $mgr->getRepository('ElektraSeedBundle:Events\UnitStatus');
        $this->eventTypeRepository = $mgr->getRepository('ElektraSeedBundle:Events\EventType');
    }

    public function createUsageEvent(UnitUsage $usage)
    {
        $event = new PartnerEvent();
        $event->setEventType($this->eventTypeRepository->findByInternalName(EventType::PARTNER));
        $event->setUsage($usage);
        $event->setText("Usage changed to '" . $usage->getName() . "'.");

        return $event;
    }

    public function createShippingEvent($status, array $options)
    {
        $event = null;
        switch($status)
        {
            case UnitStatus::RESERVED:
                $event = $this->createReserved($this->_getMandatoryOption(EventFactory::LOCATION, $options),
                    $this->_getMandatoryOption(EventFactory::REQUEST_NUMBER, $options),
                    $options);
                break;

            case UnitStatus::SHIPPED:
                $event = $this->createShipped($options);
                break;

            case UnitStatus::IN_TRANSIT:
                $event = $this->createInTransit($options);
                break;

            case UnitStatus::DELIVERED:
                $event = $this->createDelivered($this->_getMandatoryOption(EventFactory::LOCATION, $options),
                    $options);
                break;

            case UnitStatus::EXCEPTION:
                $event = $this->createException($options);
                break;

            case UnitStatus::ACKNOWLEDGE_ATTEMPT:
                $event = $this->createAcknowledgeAttempt($this->_getMandatoryOption(EventFactory::PERSON, $options),
                    $options);
                break;

            case UnitStatus::AA1SENT:
                $event = $this->createAA1Sent($this->_getMandatoryOption(EventFactory::PERSON, $options), $options);
                break;

            case UnitStatus::AA2SENT:
                $event = $this->createAA2Sent($this->_getMandatoryOption(EventFactory::PERSON, $options), $options);
                break;

            case UnitStatus::AA3SENT:
                $event = $this->createAA3Sent($this->_getMandatoryOption(EventFactory::PERSON, $options), $options);
                break;

            case UnitStatus::ESCALATION:
                $event = $this->createEscalation($this->_getMandatoryOption(EventFactory::LOCATION, $options),
                    $options);
                break;

            case UnitStatus::DELIVERY_VERIFIED:
                $event = $this->createDeliveryVerified($this->_getMandatoryOption(EventFactory::PERSON, $options),
                    $options);
                break;

            default:
                throw new \OutOfBoundsException("Invalid shipping status '" . $status . "'.");
        }

        return $event;
    }

    public function createReserved(WarehouseLocation $location, $requestNumber, array $options)
    {
        $event = $this->_createShippingEvent("Reserved for request " . $requestNumber,
            $this->unitStatusRepository->findByInternalName(UnitStatus::RESERVED),
            $location,
            $options
        );

        return $event;
    }

    public function createShipped(array $options)
    {
        $event = $this->_createShippingEvent('Unit has been shipped.',
            $this->unitStatusRepository->findByInternalName(UnitStatus::SHIPPED),
            $this->mgr
                ->getRepository('ElektraSeedBundle:Companies\GenericLocation')
                ->findByInternalName(GenericLocation::IN_TRANSIT),
            $options
        );

        return $event;
    }

    public function createInTransit(array $options)
    {
        $event = $this->_createShippingEvent('Unit is being delivered.',
            $this->unitStatusRepository->findByInternalName(UnitStatus::IN_TRANSIT),
            $this->mgr
                ->getRepository('ElektraSeedBundle:Companies\GenericLocation')
                ->findByInternalName(GenericLocation::IN_TRANSIT),
            $options
        );

        return $event;
    }

    public function createException(array $options)
    {
        $event = $this->_createShippingEvent('Exception: Unit lost in transit.',
            $this->unitStatusRepository->findByInternalName(UnitStatus::EXCEPTION),
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
            $this->unitStatusRepository->findByInternalName(UnitStatus::DELIVERED),
            $location,
            $options
        );

        return $event;
    }

    public function createAcknowledgeAttempt(CompanyPerson $person = null, array $options)
    {
        $event = $this->_createActivityEvent("Acknowledge attempted - delivery couldn't be verified.",
            $this->unitStatusRepository->findByInternalName(UnitStatus::ACKNOWLEDGE_ATTEMPT),
            $person,
            $options
        );

        return $event;
    }

    public function createAA1Sent(CompanyPerson $person = null, array $options)
    {
        $event = $this->_createActivityEvent("Acknowledge attempted - delivery couldn't be verified.",
            $this->unitStatusRepository->findByInternalName(UnitStatus::AA1SENT),
            $person,
            $options
        );

        return $event;
    }

    public function createAA2Sent(CompanyPerson $person = null, array $options)
    {
        $event = $this->_createActivityEvent("Acknowledge attempted - delivery couldn't be verified.",
            $this->unitStatusRepository->findByInternalName(UnitStatus::AA2SENT),
            $person,
            $options
        );

        return $event;
    }

    public function createAA3Sent(CompanyPerson $person = null, array $options)
    {
        $event = $this->_createActivityEvent("Acknowledge attempted - delivery couldn't be verified.",
            $this->unitStatusRepository->findByInternalName(UnitStatus::AA3SENT),
            $person,
            $options
        );

        return $event;
    }

    public function createEscalation(CompanyLocation $location = null, array $options)
    {
        $event = $this->_createShippingEvent("Escalation: Delivery couldn't be verified.",
            $this->unitStatusRepository->findByInternalName(UnitStatus::ESCALATION),
            $location,
            $options
        );

        return $event;
    }

    public function createDeliveryVerified(CompanyPerson $person = null, array $options)
    {
        $event = $this->_createActivityEvent("Delivery verified.",
            $this->unitStatusRepository->findByInternalName(UnitStatus::DELIVERY_VERIFIED),
            $person,
            $options
        );

        return $event;
    }

    private function _createActivityEvent($title, UnitStatus $unitStatus = null, CompanyPerson $person = null, array $options)
    {
        $event = new ActivityEvent();
        $this->_populateCommonFields($event, $options);

        $event->setText($title);
        $event->setEventType($this->eventTypeRepository->findByInternalName(EventType::SHIPPING));
        $event->setUnitStatus($unitStatus);
        $event->setPerson($person);

        return $event;
    }

    private function _createShippingEvent($title, UnitStatus $unitStatus = null, Location $location = null, array $options)
    {
        $event = new ShippingEvent();
        $this->_populateCommonFields($event, $options);

        $event->setText($title);
        $event->setEventType($this->eventTypeRepository->findByInternalName(EventType::SHIPPING));
        $event->setUnitStatus($unitStatus);
        $event->setLocation($location);

        return $event;
    }

    private function _getMandatoryOption($name, array $options)
    {
        if (!array_key_exists($name, $options) and !(array_key_exists(EventFactory::IGNORE_MISSING, $options) and $options[EventFactory::IGNORE_MISSING]))
            throw new \OutOfBoundsException("Mandatory option '" . $name . "' missing.");

        return array_key_exists($name, $options) ? $options[$name] : null;
    }

    private function _populateCommonFields(Event $event, array $options)
    {
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