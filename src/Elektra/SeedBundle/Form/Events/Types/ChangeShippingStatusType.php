<?php

namespace Elektra\SeedBundle\Form\Events\Types;

use Doctrine\Common\Persistence\ObjectManager;
use Elektra\SeedBundle\Controller\EventFactory;
use Elektra\SeedBundle\Entity\SeedUnits\SeedUnit;
use Elektra\SeedBundle\Entity\SeedUnits\ShippingStatus;
use Elektra\SeedBundle\Form\FormsHelper;
use Elektra\SiteBundle\Site\Helper;
use Symfony\Component\Form\FormBuilderInterface;

class ChangeShippingStatusType extends ModalFormsBaseType
{

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return "changeShippingStatus";
    }

    protected function buildFields(FormBuilderInterface $builder, array $data, ObjectManager $mgr, EventFactory $eventFactory)
    {
        $statuses = FormsHelper::getAllowedShippingStatuses($mgr, $data);

        /** @var SeedUnit $seedUnit */
        $seedUnit = $data[0];

        foreach ($statuses as $status)
        {
            /** @var ShippingStatus $status */

            $fieldName = ChangeShippingStatusType::getModalIdByInternalName($status->getInternalName());

            $event = $eventFactory->createShippingEvent($status->getInternalName(), array(
                EventFactory::IGNORE_MISSING => true,
                // WORKAROUND only necessary for delivered
                EventFactory::LOCATION => $seedUnit->getRequest()->getShippingLocation()
            ));

            switch($status->getInternalName())
            {
                case ShippingStatus::IN_TRANSIT:
                    $builder->add($fieldName, new InTransitType(), array(
                        'data' => $event,
                        'mapped' => false,
                        'label' => $status->getName(),
                        EventType::OPT_MODAL_ID => $fieldName,
                        EventType::OPT_BUTTON_NAME => ChangeShippingStatusType::BUTTON_NAME,
                    ));
                    break;

                case ShippingStatus::ACKNOWLEDGE_ATTEMPT:
                case ShippingStatus::AA1SENT:
                case ShippingStatus::AA2SENT:
                case ShippingStatus::AA3SENT:
                case ShippingStatus::DELIVERY_VERIFIED:
                case ShippingStatus::ESCALATION:
                    $builder->add($fieldName, new ActivityEventType(), array(
                        'data' => $event,
                        'mapped' => false,
                        'label' => $status->getName(),
                        EventType::OPT_MODAL_ID => $fieldName,
                        EventType::OPT_BUTTON_NAME => ChangeShippingStatusType::BUTTON_NAME,
                        ActivityEventType::OPT_LOCATION => $seedUnit->getRequest()->getShippingLocation()
                    ));
                    break;

                default:
                    $builder->add($fieldName, new ShippingEventType(), array(
                        'data' => $event,
                        'mapped' => false,
                        'label' => $status->getName(),
                        EventType::OPT_MODAL_ID => $fieldName,
                        EventType::OPT_BUTTON_NAME => ChangeShippingStatusType::BUTTON_NAME,
                    ));
                    break;
            }
        }
    }

    private static function getModalIdByInternalName($shippingStatus)
    {
        return "ShippingStatusUI_" . $shippingStatus;
    }

    public static function getModalId(ShippingStatus $shippingStatus)
    {
        return ChangeShippingStatusType::getModalIdByInternalName($shippingStatus->getInternalName());
    }
}