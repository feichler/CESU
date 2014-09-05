<?php

namespace Elektra\SeedBundle\Controller\SeedUnits;

use Doctrine\Common\Persistence\ObjectManager;
use Elektra\CrudBundle\Controller\Controller;
use Elektra\SeedBundle\Entity\Companies\GenericLocation;
use Elektra\SeedBundle\Entity\Companies\WarehouseLocation;
use Elektra\SeedBundle\Entity\EntityInterface;
use Elektra\SeedBundle\Entity\Events\EventType;
use Elektra\SeedBundle\Entity\Events\ShippingEvent;
use Elektra\SeedBundle\Entity\Events\UnitStatus;
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
    public function changeStatusAction($id = null, $status = null)
    {
        $this->initialise('changeStatus');

        /* @var SeedUnit $seedUnit  */
        $seedUnit = $this->getEntity($id);
        /* @var ObjectManager $mgr  */
        $mgr = $this->getDoctrine()->getManager();
        /* @var UnitStatus $newStatus  */
        $newStatus = $mgr
            ->getRepository($this->getCrud()->getNavigator()->getDefinition('Elektra', 'Seed', 'Events', 'UnitStatus')
                ->getClassRepository())->findByInternalName($status);
        $options = array('crud_action' => 'changeStatus');
        $eventTypeRepository = $mgr
            ->getRepository($this->getCrud()->getNavigator()->getDefinition('Elektra', 'Seed', 'Events', 'EventType')
                ->getClassRepository());

        // TODO: validate if transition is allowed

        $event = null;
        switch($status)
        {
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

        }

        if ($event != null)
        {
            $seedUnit->getEvents()->add($event);
            $event->setSeedUnit($seedUnit);
        }
        $mgr->flush();

        $returnUrl = $this->getCrud()->getNavigator()->getLink($this->getCrud()
            ->getDefinition('Elektra', 'Seed', 'SeedUnits', 'SeedUnit'), 'view', array('id' => $id));
        return $this->redirect($returnUrl);
    }
}