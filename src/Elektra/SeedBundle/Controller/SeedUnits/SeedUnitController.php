<?php

namespace Elektra\SeedBundle\Controller\SeedUnits;

use Doctrine\Common\Persistence\ObjectManager;
use Elektra\CrudBundle\Controller\Controller;
use Elektra\SeedBundle\Entity\Companies\GenericLocation;
use Elektra\SeedBundle\Entity\Companies\WarehouseLocation;
use Elektra\SeedBundle\Entity\EntityInterface;
use Elektra\SeedBundle\Entity\Events\EventType;
use Elektra\SeedBundle\Entity\Events\PartnerEvent;
use Elektra\SeedBundle\Entity\Events\ShippingEvent;
use Elektra\SeedBundle\Entity\Events\UnitStatus;
use Elektra\SeedBundle\Entity\Events\UnitUsage;
use Elektra\SeedBundle\Entity\SeedUnits\SeedUnit;
use Symfony\Component\Form\FormInterface;

class SeedUnitController extends Controller
{

    protected function getDefinition()
    {

        return $this->get('navigator')->getDefinition('Elektra', 'Seed', 'SeedUnits', 'SeedUnit');
    }

    public function beforeAddEntity(EntityInterface $entity, FormInterface $form = null)
    {

        if ($entity instanceof SeedUnit) {
            $location = $form->get('location')->getData();
            if ($location instanceof WarehouseLocation) {
                $event = new ShippingEvent();
                $event->setLocation($location);
                $event->setSeedUnit($entity);
                $event->setTimestamp(time());
                $event->setText('Seed Unit created');
                $event->setTitle('Seed Unit created');
                $entity->getEvents()->add($event);

                $em           = $this->getDoctrine()->getManager();
                $rep          = $em->getRepository($this->getCrud()->getNavigator()->getDefinition('Elektra', 'Seed', 'Events', 'EventType')->getClassRepository());
                $shippingType = $rep->findOneBy(array('internalName' => 'shipping'));
                $event->setEventType($shippingType);
                $em->persist($event);
            }
        }

        return true;
    }

    /**
     * @param int $id
     * @param string $status
     */
    public function changeShippingStatusAction($id = null, $status = null)
    {
        $this->initialise('changeShippingStatus');

        /* @var SeedUnit $seedUnit  */
        $seedUnit = $this->getEntity($id);
        /* @var ObjectManager $mgr  */
        $mgr = $this->getDoctrine()->getManager();
        /* @var UnitStatus $newStatus  */
        $newStatus = $mgr
            ->getRepository($this->getCrud()->getNavigator()->getDefinition('Elektra', 'Seed', 'Events', 'UnitStatus')
                ->getClassRepository())->findByInternalName($status);
        $options = array('crud_action' => 'changeShippingStatus');
        $eventTypeRepository = $mgr
            ->getRepository($this->getCrud()->getNavigator()->getDefinition('Elektra', 'Seed', 'Events', 'EventType')
                ->getClassRepository());

        // TODO: validate if transition is allowed

        $event = null;
        switch($status)
        {
            case UnitStatus::SHIPPED:
                $event = new ShippingEvent();
                $event->setEventType($eventTypeRepository->findByInternalName(EventType::SHIPPING));
                $event->setUnitStatus($newStatus);
                $event->setLocation($mgr
                    ->getRepository($this->getCrud()->getNavigator()->getDefinition('Elektra', 'Seed', 'Companies', 'GenericLocation')
                        ->getClassRepository())->findByInternalName(GenericLocation::IN_TRANSIT));
                $event->setTitle('Unit has been shipped.');
                break;

            case UnitStatus::IN_TRANSIT:
                $event = new ShippingEvent();
                $event->setEventType($eventTypeRepository->findByInternalName(EventType::SHIPPING));
                $event->setUnitStatus($newStatus);
                $event->setLocation($mgr
                    ->getRepository($this->getCrud()->getNavigator()->getDefinition('Elektra', 'Seed', 'Companies', 'GenericLocation')
                        ->getClassRepository())->findByInternalName(GenericLocation::IN_TRANSIT));
                $event->setTitle('Unit is being delivered.');
                break;

            case UnitStatus::DELIVERED:
                $event = new ShippingEvent();
                $event->setEventType($eventTypeRepository->findByInternalName(EventType::SHIPPING));
                $event->setUnitStatus($newStatus);
                $event->setLocation($seedUnit->getRequest()->getShippingLocation());
                $event->setTitle('Unit arrived at target location.');
                break;

            case UnitStatus::EXCEPTION:
                $event = new ShippingEvent();
                $event->setEventType($eventTypeRepository->findByInternalName(EventType::SHIPPING));
                $event->setUnitStatus($newStatus);
                $event->setLocation($mgr
                    ->getRepository($this->getCrud()->getNavigator()->getDefinition('Elektra', 'Seed', 'Companies', 'GenericLocation')
                        ->getClassRepository())->findByInternalName(GenericLocation::UNKNOWN));
                $event->setTitle('Exception: Unit lost in transit.');
                break;

            case UnitStatus::ACKNOWLEDGE_ATTEMPT:
                $event = new ShippingEvent();
                $event->setEventType($eventTypeRepository->findByInternalName(EventType::SHIPPING));
                $event->setUnitStatus($newStatus);
                $event->setLocation($seedUnit->getRequest()->getShippingLocation());
                $event->setTitle("Acknowledge attempted - delivery couldn't be verified.");
                break;

            case UnitStatus::AA1SENT:
                $event = new ShippingEvent();
                $event->setEventType($eventTypeRepository->findByInternalName(EventType::SHIPPING));
                $event->setUnitStatus($newStatus);
                $event->setLocation($seedUnit->getRequest()->getShippingLocation());
                $event->setTitle("AA1 attempted - delivery couldn't be verified.");
                break;

            case UnitStatus::AA2SENT:
                $event = new ShippingEvent();
                $event->setEventType($eventTypeRepository->findByInternalName(EventType::SHIPPING));
                $event->setUnitStatus($newStatus);
                $event->setLocation($seedUnit->getRequest()->getShippingLocation());
                $event->setTitle("AA2 attempted - delivery couldn't be verified.");
                break;

            case UnitStatus::AA3SENT:
                $event = new ShippingEvent();
                $event->setEventType($eventTypeRepository->findByInternalName(EventType::SHIPPING));
                $event->setUnitStatus($newStatus);
                $event->setLocation($seedUnit->getRequest()->getShippingLocation());
                $event->setTitle("AA3 attempted - delivery couldn't be verified.");
                break;

            case UnitStatus::ESCALATION:
                $event = new ShippingEvent();
                $event->setEventType($eventTypeRepository->findByInternalName(EventType::SHIPPING));
                $event->setUnitStatus($newStatus);
                $event->setLocation($seedUnit->getRequest()->getShippingLocation());
                $event->setTitle("Escalation: Delivery couldn't be verified.");
                break;

            case UnitStatus::DELIVERY_VERIFIED:
                $event = new ShippingEvent();
                $event->setEventType($eventTypeRepository->findByInternalName(EventType::SHIPPING));
                $event->setUnitStatus($newStatus);
                $event->setLocation($seedUnit->getRequest()->getShippingLocation());
                $event->setTitle("Delivery verified.");
                break;
        }

        if ($event != null)
        {
            $seedUnit->getEvents()->add($event);
            $event->setSeedUnit($seedUnit);
            $mgr->flush();
        }

        $returnUrl = $this->getCrud()->getNavigator()->getLink($this->getCrud()
            ->getDefinition('Elektra', 'Seed', 'SeedUnits', 'SeedUnit'), 'view', array('id' => $id));
        return $this->redirect($returnUrl);
    }

    /**
     * @param int $id
     * @param int $statusId
     */
    public function changeSalesStatusAction($id = null, $statusId = null)
    {
        $this->initialise('changeSalesStatus');

        /* @var SeedUnit $seedUnit  */
        $seedUnit = $this->getEntity($id);
        /* @var ObjectManager $mgr  */
        $mgr = $this->getDoctrine()->getManager();
        /* @var UnitStatus $newStatus  */
        $newStatus = $mgr
            ->getRepository($this->getCrud()->getNavigator()->getDefinition('Elektra', 'Seed', 'Events', 'UnitStatus')
                ->getClassRepository())->find($statusId);
        $options = array('crud_action' => 'changeSalesStatus');
        $eventTypeRepository = $mgr
            ->getRepository($this->getCrud()->getNavigator()->getDefinition('Elektra', 'Seed', 'Events', 'EventType')
                ->getClassRepository());

    }

    /**
     * @param int $id
     * @param int $usageId
     */
    public function changeUsageAction($id = null, $usageId = null)
    {
        $this->initialise('changeUsage');

        /* @var SeedUnit $seedUnit  */
        $seedUnit = $this->getEntity($id);
        /* @var ObjectManager $mgr  */
        $mgr = $this->getDoctrine()->getManager();
        /* @var UnitUsage $newUsage  */
        $newUsage = $mgr
            ->getRepository($this->getCrud()->getNavigator()->getDefinition('Elektra', 'Seed', 'Events', 'UnitUsage')
                ->getClassRepository())->find($usageId);
        $options = array('crud_action' => 'changeUsage');
        $eventTypeRepository = $mgr
            ->getRepository($this->getCrud()->getNavigator()->getDefinition('Elektra', 'Seed', 'Events', 'EventType')
                ->getClassRepository());

        $event = new PartnerEvent();
        $event->setEventType($eventTypeRepository->findByInternalName(EventType::PARTNER));
        $event->setUsage($newUsage);
        $event->setTitle("Usage changed to '" . $newUsage->getName() . "'.");

        $seedUnit->getEvents()->add($event);
        $event->setSeedUnit($seedUnit);
        $mgr->flush();

        $returnUrl = $this->getCrud()->getNavigator()->getLink($this->getCrud()
            ->getDefinition('Elektra', 'Seed', 'SeedUnits', 'SeedUnit'), 'view', array('id' => $id));
        return $this->redirect($returnUrl);
    }
}