<?php

namespace Elektra\SeedBundle\Controller\Companies;

use Elektra\CrudBundle\Controller\Controller;
use Elektra\CrudBundle\Crud\Definition;
use Elektra\SeedBundle\Entity\Companies\Customer;
use Elektra\SeedBundle\Entity\Companies\Partner;
use Elektra\SeedBundle\Entity\EntityInterface;
use Symfony\Component\Form\FormInterface;

class CustomerController extends Controller
{

    /**
     * @return Definition
     */
    protected function getDefinition()
    {

        return $this->get('navigator')->getDefinition('Elektra', 'Seed', 'Companies', 'Customer');
    }

    public function beforeAddEntity(EntityInterface $entity, FormInterface $form = null)
    {

        $partnerField = $form->get('group_common')->get('partner');
        $partnerId = $partnerField->getViewData();

        $manager   = $this->getDoctrine()->getManager();
        $partner   = $manager->find($this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'Partner')->getClassEntity(), $partnerId);

        $entity->getPartners()->add($partner);
        $partner->getCustomers()->add($entity);

//        return parent::beforeAddEntity($entity, $form); // TODO: Change the autogenerated stub
        return true;
    }

    public function beforeEditEntity(EntityInterface $entity, FormInterface $form = null)
    {
        /** @var Customer $entity */

        echo "1";
        $partnerField = $form->get('group_common')->get('partner');
        $partnerId = $partnerField->getViewData();
        echo "2";

        $manager   = $this->getDoctrine()->getManager();
        /** @var Partner $partner */
        $partner   = $manager->find($this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'Partner')->getClassEntity(), $partnerId);
        echo "3";

        /** @var Partner $oldPartner */
        $oldPartner = $entity->getPartners()->first();
        $oldPartner->getCustomers()->removeElement($entity);

        $entity->getPartners()->removeElement($oldPartner);

        $entity->getPartners()->add($partner);
        $partner->getCustomers()->add($entity);
        echo "4";

//        return parent::beforeAddEntity($entity, $form); // TODO: Change the autogenerated stub
        return true;
    }
}