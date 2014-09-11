<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Controller\Requests;

use Doctrine\ORM\EntityManager;
use Elektra\CrudBundle\Controller\Controller;
use Elektra\SeedBundle\Entity\EntityInterface;
use Elektra\SeedBundle\Entity\Events\EventType;
use Elektra\SeedBundle\Entity\Events\ShippingEvent;
use Elektra\SeedBundle\Entity\Events\UnitStatus;
use Elektra\SeedBundle\Entity\Requests\Request;
use Elektra\SeedBundle\Entity\SeedUnits\SeedUnit;
use Elektra\SeedBundle\Form\Requests\AddUnitsType;
use Elektra\SiteBundle\Site\Helper;
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

            /* @var $su SeedUnit */
            foreach ($entity->getSeedUnits() as $su) {
                $su->setRequest($entity);
                $shippingEvent = new ShippingEvent();
                $shippingEvent->setTitle("Reserved for request " . $entity->getRequestNumber());
                $shippingEvent->setLocation($su->getLocation());
                $shippingEvent->setUnitStatus($manager->getRepository('ElektraSeedBundle:Events\UnitStatus')->findByInternalName(UnitStatus::RESERVED));
                $shippingEvent->setEventType($manager->getRepository('ElektraSeedBundle:Events\EventType')->findByInternalName(EventType::SHIPPING));
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