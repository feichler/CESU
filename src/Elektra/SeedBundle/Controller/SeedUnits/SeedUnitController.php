<?php

namespace Elektra\SeedBundle\Controller\SeedUnits;

use Doctrine\Common\Persistence\ObjectManager;
use Elektra\CrudBundle\Controller\Controller;
use Elektra\SeedBundle\Controller\EventFactory;
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

        // TODO: validate if transition is allowed

        $eventFactory = new EventFactory($mgr);

        $location = null;
        switch($status)
        {
            case UnitStatus::DELIVERED:
            case UnitStatus::ACKNOWLEDGE_ATTEMPT:
            case UnitStatus::AA1SENT:
            case UnitStatus::AA2SENT:
            case UnitStatus::AA3SENT:
            case UnitStatus::ESCALATION:
            case UnitStatus::DELIVERY_VERIFIED:
                $location = $seedUnit->getRequest()->getShippingLocation();
                break;
        }

        $event = $eventFactory->createShippingEvent($status, array(
            EventFactory::LOCATION => $location
        ));

        $seedUnit->getEvents()->add($event);
        $event->setSeedUnit($seedUnit);
        $mgr->flush();

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

        $eventFactory = new EventFactory($mgr);
        $event = $eventFactory->createUsageEvent($newUsage);

        $seedUnit->getEvents()->add($event);
        $event->setSeedUnit($seedUnit);
        $mgr->flush();

        $returnUrl = $this->getCrud()->getNavigator()->getLink($this->getCrud()
            ->getDefinition('Elektra', 'Seed', 'SeedUnits', 'SeedUnit'), 'view', array('id' => $id));
        return $this->redirect($returnUrl);
    }
}