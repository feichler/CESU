<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Controller\Requests;

use Elektra\CrudBundle\Controller\Controller;
use Elektra\SeedBundle\Entity\EntityInterface;
use Elektra\SeedBundle\Form\Requests\AddUnitsType;
use Symfony\Component\Form\FormInterface;

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

    public function addUnitAction($id = null)
    {

        $this->initialise('addUnits');

        // get the existing entity
        $entity = $this->getEntity($id);

        $options = array('crud_action' => 'addUnits');

        // get the associated form
        $form = $this->createForm(new AddUnitsType($this->getCrud()), $entity, $options);

        $form->handleRequest($this->getCrud()->getRequest());

        if ($form->isValid()) {
            foreach ($entity->getSeedUnits() as $su)
            {
                $su->setRequest($entity);
            }
            $manager = $this->getDoctrine()->getManager();
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