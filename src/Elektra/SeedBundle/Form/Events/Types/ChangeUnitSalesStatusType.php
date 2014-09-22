<?php

namespace Elektra\SeedBundle\Form\Events\Types;

use Doctrine\Common\Persistence\ObjectManager;
use Elektra\SeedBundle\Controller\EventFactory;
use Elektra\SeedBundle\Entity\Events\UnitSalesStatus;
use Elektra\SeedBundle\Entity\SeedUnits\SeedUnit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ChangeUnitSalesStatusType extends ModalFormsBaseType
{

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return "changeUnitSalesStatus";
    }

    protected function buildFields(FormBuilderInterface $builder, array $data, ObjectManager $mgr, EventFactory $eventFactory)
    {
        $salesStatuses = $mgr->getRepository('ElektraSeedBundle:Events\UnitSalesStatus')->findAll();

        foreach ($salesStatuses as $salesStatus)
        {
            $fieldName = ChangeUnitSalesStatusType::getModalId($salesStatus);

            $event = $eventFactory->createSalesEvent($salesStatus, array());

            $builder->add($fieldName, new EventType(), array(
                'data' => $event,
                'mapped' => false,
                EventType::OPT_MODAL_ID => $fieldName,
                EventType::OPT_BUTTON_NAME => ChangeUnitSalesStatusType::BUTTON_NAME,
            ));
        }
    }

    public static function getModalId(UnitSalesStatus $salesStatus)
    {
        return "SalesStatusUI_" . $salesStatus->getAbbreviation();
    }
}