<?php

namespace Elektra\SeedBundle\Form\Events\Types;

use Doctrine\Common\Persistence\ObjectManager;
use Elektra\SeedBundle\Controller\EventFactory;
use Elektra\SeedBundle\Entity\Events\UnitStatus;
use Elektra\SeedBundle\Entity\SeedUnits\SeedUnit;
use Elektra\SeedBundle\Form\FormsHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ChangeUnitStatusType extends AbstractType
{
    const OPT_DATA = 'data';
    const OPT_OBJECT_MANAGER = 'objectManager';
    const BUTTON_NAME = 'changeShippingStatus';

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return "changeShippingStatus";
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $data = $options[ChangeUnitUsageType::OPT_DATA];

        if (is_array($data))
        {
            // TODO validate elements
        }
        else if ($data instanceof SeedUnit)
        {
            $data = array($data);
        }
        else
        {
            // TODO exception
        }

        /** @var ObjectManager $mgr */
        $mgr = $options[ChangeUnitStatusType::OPT_OBJECT_MANAGER];

        /** @var EventFactory $eventFactory */
        $eventFactory = new EventFactory($mgr);

        $this->buildFields($builder, $data, $mgr, $eventFactory);
    }

    private function buildFields(FormBuilderInterface $builder, array $data, ObjectManager $mgr, EventFactory $eventFactory)
    {
        $statuses = FormsHelper::getAllowedStatuses($data);

        foreach ($statuses as $status)
        {
            $fieldName = ChangeUnitStatusType::getModalId($status);

            $event = $eventFactory->createShippingEvent($status, array(
                EventFactory::IGNORE_MISSING => true,
                // WORKAROUND only necessary for delivered
                EventFactory::LOCATION => $data[0]->getRequest()->getShippingLocation()
            ));

            switch($status)
            {
                case UnitStatus::IN_TRANSIT:
                    $builder->add($fieldName, new InTransitType(), array(
                        'data' => $event,
                        'mapped' => false,
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
                        EventType::OPT_MODAL_ID => $fieldName,
                        EventType::OPT_BUTTON_NAME => ChangeUnitStatusType::BUTTON_NAME,
                        ActivityEventType::OPT_LOCATION => $data[0]->getRequest()->getShippingLocation()
                    ));
                    break;

                default:
                    $builder->add($fieldName, new UnitShippingEventType(), array(
                        'data' => $event,
                        'mapped' => false,
                        EventType::OPT_MODAL_ID => $fieldName,
                        EventType::OPT_BUTTON_NAME => ChangeUnitStatusType::BUTTON_NAME,
                    ));
                    break;
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setRequired(array(
           ChangeUnitStatusType::OPT_DATA, ChangeUnitStatusType::OPT_OBJECT_MANAGER
        ));
        $resolver->setDefaults(array(
            'label' => false
        ));
    }

    public static function getModalId(UnitStatus $shippingStatus)
    {
        return "ShippingStatusUI_" . $shippingStatus->getInternalName();
    }
}