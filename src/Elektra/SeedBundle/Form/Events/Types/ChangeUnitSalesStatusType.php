<?php

namespace Elektra\SeedBundle\Form\Events\Types;

use Doctrine\Common\Persistence\ObjectManager;
use Elektra\SeedBundle\Controller\EventFactory;
use Elektra\SeedBundle\Entity\Events\UnitSalesStatus;
use Elektra\SiteBundle\Site\Helper;
use Symfony\Component\Form\FormBuilderInterface;

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

        foreach ($salesStatuses as $salesStatus) {
            $fieldName = ChangeUnitSalesStatusType::getModalId($salesStatus);

            $event = $eventFactory->createSalesEvent($salesStatus, array());

            $builder->add(
                $fieldName,
                new SalesEventType(),
                array(
                    'data'                     => $event,
                    'mapped'                   => false,
                    'label' => $salesStatus->getName(),
//                    'label'                    => Helper::translate('modal.header.sales.' . $salesStatus),
                    EventType::OPT_MODAL_ID    => $fieldName,
                    EventType::OPT_BUTTON_NAME => ChangeUnitSalesStatusType::BUTTON_NAME,
                )
            );
        }
    }

    public static function getModalId(UnitSalesStatus $salesStatus)
    {

        return "SalesStatusUI_" . $salesStatus->getAbbreviation();
    }
}