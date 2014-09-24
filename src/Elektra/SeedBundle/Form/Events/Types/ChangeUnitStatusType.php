<?php

namespace Elektra\SeedBundle\Form\Events\Types;

use Doctrine\Common\Persistence\ObjectManager;
use Elektra\SeedBundle\Controller\EventFactory;
use Elektra\SeedBundle\Entity\Companies\Partner;
use Elektra\SeedBundle\Entity\Events\UnitStatus;
use Elektra\SeedBundle\Entity\SeedUnits\SeedUnit;
use Elektra\SeedBundle\Form\FormsHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ChangeUnitStatusType extends ModalFormsBaseType
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
        $statuses = FormsHelper::getAllowedStatuses($data);

        /** @var SeedUnit $seedUnit */
        $seedUnit = $data[0];
        /** @var Partner $partner */
        $partner = $seedUnit->getRequest()->getCompany();

        foreach ($statuses as $status)
        {
            $fieldName = ChangeUnitStatusType::getModalIdByInternalName($status);

            $event = $eventFactory->createShippingEvent($status, array(
                EventFactory::IGNORE_MISSING => true,
                // WORKAROUND only necessary for delivered
                EventFactory::LOCATION => $seedUnit->getRequest()->getShippingLocation()
            ));

            switch($status)
            {
                case UnitStatus::IN_TRANSIT:
                    $builder->add($fieldName, new InTransitType(), array(
                        'data' => $event,
                        'mapped' => false,
                        EventType::OPT_PARTNER => $partner,
                        EventType::OPT_MODAL_ID => $fieldName,
                        EventType::OPT_BUTTON_NAME => ChangeUnitStatusType::BUTTON_NAME,
                    ));
                    break;

                case UnitStatus::ACKNOWLEDGE_ATTEMPT:
                case UnitStatus::AA1SENT:
                case UnitStatus::AA2SENT:
                case UnitStatus::AA3SENT:
                case UnitStatus::DELIVERY_VERIFIED:
                    $builder->add($fieldName, new ActivityEventType(), array(
                        'data' => $event,
                        'mapped' => false,
                        EventType::OPT_PARTNER => $partner,
                        EventType::OPT_MODAL_ID => $fieldName,
                        EventType::OPT_BUTTON_NAME => ChangeUnitStatusType::BUTTON_NAME,
                        ActivityEventType::OPT_LOCATION => $seedUnit->getRequest()->getShippingLocation()
                    ));
                    break;

                default:
                    $builder->add($fieldName, new EventType(), array(
                        'data' => $event,
                        'mapped' => false,
                        EventType::OPT_PARTNER => $partner,
                        EventType::OPT_MODAL_ID => $fieldName,
                        EventType::OPT_BUTTON_NAME => ChangeUnitStatusType::BUTTON_NAME,
                    ));
                    break;
            }
        }
    }

    private static function getModalIdByInternalName($shippingStatus)
    {
        return "ShippingStatusUI_" . $shippingStatus;
    }

    public static function getModalId(UnitStatus $shippingStatus)
    {
        return ChangeUnitStatusType::getModalIdByInternalName($shippingStatus->getInternalName());
    }
}