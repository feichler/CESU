<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Controller\Requests;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Elektra\CrudBundle\Controller\Controller;
use Elektra\SeedBundle\Controller\EventFactory;
use Elektra\SeedBundle\Entity\EntityInterface;
use Elektra\SeedBundle\Entity\Events\EventType;
use Elektra\SeedBundle\Entity\Events\ShippingEvent;
use Elektra\SeedBundle\Entity\Events\StatusEvent;
use Elektra\SeedBundle\Entity\Events\UnitStatus;
use Elektra\SeedBundle\Entity\Requests\Request;
use Elektra\SeedBundle\Entity\SeedUnits\SeedUnit;
use Elektra\SeedBundle\Form\Events\Types\UnitStatusEventType;
use Elektra\SeedBundle\Form\Requests\AddUnitsType;
use Elektra\SiteBundle\Site\Helper;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\ConstraintViolation;

/**
 * Class RequestController
 *
 * @package   Elektra\SeedBundle\Controller\Requests
 *
 * @version   0.1-dev
 */
class RequestController extends Controller
{

    /**
     * {@inheritdoc}
     */
    protected function getDefinition()
    {

        return $this->get('navigator')->getDefinition('Elektra', 'Seed', 'Requests', 'Request');
    }

    public function changeShippingStatusAction($id)
    {
        $this->initialise('changeShippingStatus');

        // get the existing entity
        /** @var Request $entity */
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
                $ids = $this->getSelectedSeedUnitIds($form);
                $event = $this->getChangeStatusEvent($form);

                $this->processShippingStatusChange($entity, $ids, $event);
            }
        }

        $returnUrl = $this->getCrud()->getNavigator()->getLink($this->getCrud()
            ->getDefinition('Elektra', 'Seed', 'Requests', 'Request'), 'view', array('id' => $id));
        return $this->redirect($returnUrl);
    }

    private function getChangeStatusEvent(Form $form)
    {
        $event = null;

        if ($form->getClickedButton()->getName() == 'changeStatus')
        {
            $parent = $form->getClickedButton()->getParent()->getParent();
            $event = $parent->getData();

        }
        return $event;
    }

    /**
     * @param Form $form
     * @return array
     */
    private function getSelectedSeedUnitIds(Form $form)
    {
        $ids = array();
        foreach(array_values($form->get('group_units')->get('seedUnits')->getViewData()) as $id)
        {
            array_push($ids, intval($id));
        }

        return $ids;
    }

    private function processShippingStatusChange(Request $request, array $ids, StatusEvent $eventTemplate)
    {
        /** @var $mgr EntityManager */
        $mgr = $this->getDoctrine()->getManager();

        $newStatus = $eventTemplate->getUnitStatus()->getInternalName();
        $allowedStatuses = isset(UnitStatus::$ALLOWED_TO[$newStatus]) ? UnitStatus::$ALLOWED_TO[$newStatus] : array();

        // retrieve seed units
        $repo = $mgr->getRepository('ElektraSeedBundle:SeedUnits\SeedUnit');

        foreach($ids as $id)
        {
            $seedUnit = $repo->find($id);

            if (in_array($seedUnit->getUnitStatus()->getInternalName(), $allowedStatuses))
            {

                $event = clone $eventTemplate;
                $event->setAudits(new ArrayCollection());
                $seedUnit->getEvents()->add($event);
                $event->setSeedUnit($seedUnit);
            }
        }

        $mgr->flush();
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
        $form = $this->getForm($entity, 'view', 'request.changeShippingStatus');

        // get the view name (specific or common) and prepare the form view
        $viewName = $this->getCrud()->getView('view');
        $formView = $form->createView();

        // render the add-view with the form
        return $this->render($viewName, array('form' => $formView));
    }

    public function addUnitAction($id = null, $page = null)
    {

        $this->initialise('addUnits');

        $this->getCrud()->setData('page', $page, 'addUnits');
        $this->getCrud()->setData('id', $id, 'addUnits');

        // get the existing entity
        /* @var $entity Request */
        $entity = $this->getEntity($id);

        $options = Helper::mergeOptions(array('crud_action' => 'addUnits'), $this->getFormOptions($entity, 'addUnits'));

        $form = $this->createForm(new AddUnitsType($this->getCrud()), $entity, $options);

        $form->handleRequest($this->getCrud()->getRequest());

        if ($form->isValid() && !$this->filterSubmitted) {
            /* @var $manager EntityManager */
            $manager = $this->getDoctrine()->getManager();

            $eventFactory = new EventFactory($manager);
            /* @var $su SeedUnit */
            foreach ($entity->getSeedUnits() as $su) {
                $su->setRequest($entity);
                $shippingEvent = $eventFactory->createReserved($su->getLocation(), $entity->getRequestNumber(), array());
                $shippingEvent->setSeedUnit($su);
                $su->getEvents()->add($shippingEvent);
            }
            $manager->flush();

            $returnUrl = $this->getCrud()->getLinker()->getRedirectAfterProcess($entity);
            return $this->redirect($returnUrl);
        }

        // get the view name (specific or common) and prepare the form view
        $viewName = $this->getCrud()->getView('form');
        $formView = $form->createView();

        // render the add-view with the form
        return $this->render($viewName, array('form' => $formView));
    }



    /**
     * @param EntityInterface $entity
     *
     * @return bool
     */
    public function beforeAddUnitsEntity(EntityInterface $entity, FormInterface $form = null)
    {

        // NOTE override if necessary
        // DEFINE: if false is returned, the action won't be processed

        return true;
    }

    /**
     * @param EntityInterface $entity
     */
    public function afterAddUnitsEntity(EntityInterface $entity, FormInterface $form = null)
    {
        // NOTE override if necessary
    }
}