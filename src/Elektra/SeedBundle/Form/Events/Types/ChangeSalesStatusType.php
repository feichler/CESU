<?php

namespace Elektra\SeedBundle\Form\Events\Types;

use Doctrine\Common\Persistence\ObjectManager;
use Elektra\SeedBundle\Controller\EventFactory;
use Elektra\SeedBundle\Entity\SeedUnits\SalesStatus;
use Symfony\Component\Form\FormBuilderInterface;

class ChangeSalesStatusType extends ModalFormsBaseType
{

    /**
     * @inheritdoc
     */
    public function getName()
    {

        return "changeSalesStatus";
    }

    protected function buildFields(FormBuilderInterface $builder, array $data, ObjectManager $mgr, EventFactory $eventFactory)
    {

        $salesStatuses = $mgr->getRepository('ElektraSeedBundle:SeedUnits\SalesStatus')->findAll();

        foreach ($salesStatuses as $salesStatus) {
            $fieldName = ChangeSalesStatusType::getModalId($salesStatus);

            $event = $eventFactory->createSalesEvent($salesStatus, array());

            $builder->add(
                $fieldName,
                new SalesEventType(),
                array(
                    'data'                     => $event,
                    'mapped'                   => false,
                    'label' => $salesStatus->getName(),
                    EventType::OPT_MODAL_ID    => $fieldName,
                    EventType::OPT_BUTTON_NAME => ChangeSalesStatusType::BUTTON_NAME,
                )
            );
        }
    }

    public static function getModalId(SalesStatus $salesStatus)
    {

        return "SalesStatusUI_" . $salesStatus->getAbbreviation();
    }
}