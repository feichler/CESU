<?php

namespace Elektra\SeedBundle\Controller\SeedUnits;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Elektra\CrudBundle\Controller\Controller;
use Elektra\SeedBundle\Controller\EventFactory;
use Elektra\SeedBundle\Entity\Companies\GenericLocation;
use Elektra\SeedBundle\Entity\Companies\WarehouseLocation;
use Elektra\SeedBundle\Entity\EntityInterface;
use Elektra\SeedBundle\Entity\Events\Event;
use Elektra\SeedBundle\Entity\Events\EventType;
use Elektra\SeedBundle\Entity\Events\PartnerEvent;
use Elektra\SeedBundle\Entity\Events\SalesEvent;
use Elektra\SeedBundle\Entity\Events\ShippingEvent;
use Elektra\SeedBundle\Entity\Events\StatusEvent;
use Elektra\SeedBundle\Entity\Events\UnitStatus;
use Elektra\SeedBundle\Entity\Events\UnitUsage;
use Elektra\SeedBundle\Entity\SeedUnits\SeedUnit;
use Elektra\SeedBundle\Form\Events\Types\ChangeUnitSalesStatusType;
use Elektra\SeedBundle\Form\Events\Types\ChangeUnitStatusType;
use Elektra\SeedBundle\Form\Events\Types\ChangeUnitUsageType;
use Elektra\SeedBundle\Form\Events\Types\Strategies\ProcessEventStrategyInterface;
use Elektra\SeedBundle\Form\Events\Types\Strategies\ProcessSalesEventStrategy;
use Elektra\SeedBundle\Form\Events\Types\Strategies\ProcessShippingEventStrategy;
use Elektra\SeedBundle\Form\Events\Types\Strategies\ProcessUsageEventStrategy;
use Elektra\SeedBundle\Repository\Events\EventTypeRepository;
use Elektra\SeedBundle\Repository\Events\UnitStatusRepository;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;

class SeedUnitController extends Controller
{

    protected function getDefinition()
    {

        return $this->get('navigator')->getDefinition('Elektra', 'Seed', 'SeedUnits', 'SeedUnit');
    }

    public function beforeAddEntity(EntityInterface $entity, FormInterface $form = null)
    {

        if ($entity instanceof SeedUnit)
        {
            $location = $form->get('group_common')->get('location')->getData();

            if ($location instanceof WarehouseLocation)
            {
                // get the unit status
                $status = $this->getDoctrine()
                    ->getRepository($this->getCrud()->getDefinition('Elektra', 'Seed', 'Events', 'UnitStatus')->getClassRepository())
                    ->findByInternalName(UnitStatus::AVAILABLE);

                $event = new ShippingEvent();
                $event->setLocation($location);
                $event->setSeedUnit($entity);
                $event->setTimestamp(time());
                $event->setComment('Seed Unit created');
                $event->setText('Seed Unit created');
                $event->setUnitStatus($status);
                $entity->getEvents()->add($event);

                $eventType = $this->getDoctrine()
                    ->getRepository($this->getCrud()->getNavigator()->getDefinition('Elektra', 'Seed', 'Events', 'EventType')->getClassRepository())
                    ->findByInternalName(EventType::SHIPPING);

                $event->setEventType($eventType);

                $em = $this->getDoctrine()->getManager();
                $em->persist($event);
            }
        }

        return true;
    }

    public function addEventAction($id)
    {

        $this->initialise('addEvent');

        // get the existing entity
        /** @var SeedUnit $entity */
        $entity = $this->getEntity($id);

        // get the associated form
        $form = $this->getForm($entity, 'view');

        // check the form
        $form->handleRequest($this->getCrud()->getRequest());

        /** @var $mgr EntityManager */
        $mgr = $this->getDoctrine()->getManager();

        // WORKAROUND: $form->handleRequest sets all fields null -> undo required!!
        $mgr->refresh($entity);

        if ($form instanceof Form)
        {
            if ($this->getCrud()->getRequest()->getMethod() == 'POST')
            {
                $shippingEvent = $this->getSelectedEvent($form);
                $this->processEvent($id, $shippingEvent);
            }
        }

        $returnUrl = $this->getCrud()->getNavigator()->getLink(
            $this->getCrud()->getDefinition('Elektra', 'Seed', 'SeedUnits', 'SeedUnit'),
            'view',
            array('id' => $id)
        );

        return $this->redirect($returnUrl);
    }

    private function getSelectedEvent(Form $form)
    {

        $event = null;

        if (in_array($form->getClickedButton()->getName(), array(ChangeUnitStatusType::BUTTON_NAME, ChangeUnitUsageType::BUTTON_NAME, ChangeUnitSalesStatusType::BUTTON_NAME)))
        {
            $parent = $form->getClickedButton()->getParent()->getParent();
            $event  = $parent->getData();
        }

        return $event;
    }

    private function processEvent($id, Event $eventTemplate)
    {
        /** @var ProcessEventStrategyInterface $processStrategy */
        $processStrategy = null;
        if ($eventTemplate instanceof StatusEvent)
        {
            $processStrategy = new ProcessShippingEventStrategy();
        }
        else if ($eventTemplate instanceof PartnerEvent)
        {
            $processStrategy = new ProcessUsageEventStrategy();
        }
        else if ($eventTemplate instanceof SalesEvent)
        {
            $processStrategy = new ProcessSalesEventStrategy();
        }

        /** @var $mgr EntityManager */
        $mgr = $this->getDoctrine()->getManager();

        // retrieve seed units
        $repo = $mgr->getRepository('ElektraSeedBundle:SeedUnits\SeedUnit');

        $processStrategy->prepare($eventTemplate);

        /** @var SeedUnit $seedUnit */
        $seedUnit = $repo->find($id);

        if ($processStrategy->isAllowed($seedUnit, $eventTemplate))
        {
            $event = $eventTemplate->createClone();
            $seedUnit->getEvents()->add($event);
            $event->setSeedUnit($seedUnit);

            $processStrategy->process($seedUnit, $event);
            $mgr->flush();
        }
    }

    /**
     * @inheritdoc
     */
    public function viewAction($id)
    {

        $this->initialise('view');

        // get the existing entity
        $entity = $this->getEntity($id);

        // get the associated form
        $form = $this->getForm($entity, 'view', 'seedUnit.addEvent');

        // get the view name (specific or common) and prepare the form view
        $viewName = $this->getCrud()->getView('view');
        $formView = $form->createView();

        // render the add-view with the form
        return $this->render($viewName, array('form' => $formView));
    }
}