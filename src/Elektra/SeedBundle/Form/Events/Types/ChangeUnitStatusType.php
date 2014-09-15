<?php

namespace Elektra\SeedBundle\Form\Events\Types;

use Elektra\SeedBundle\Entity\Events\ActivityEvent;
use Elektra\SeedBundle\Entity\Events\ShippingEvent;
use Elektra\SeedBundle\Entity\Events\UnitStatus;
use Elektra\SeedBundle\Entity\SeedUnits\SeedUnit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ChangeUnitStatusType extends AbstractType
{
    const OPT_DATA = 'data';

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return "changeunitstatus";
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $data = $options[ChangeUnitStatusType::OPT_DATA];

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

        // calculate allowed target statuses
        $statuses = array();
        foreach ($data as $seedUnit) {
            $allowed  = UnitStatus::$ALLOWED_FROM[$seedUnit->getUnitStatus()->getInternalName()];
            $statuses = array_merge($statuses, $allowed);
        }

        $statuses = array_unique($statuses);

        foreach ($statuses as $status)
        {
            $fieldName = $status . "StatusUI";

            switch($status)
            {
                case UnitStatus::IN_TRANSIT:
                    $builder->add($fieldName, new InTransitType(), array(
                        'mapped' => false
                    ));
                    break;

                case UnitStatus::ACKNOWLEDGE_ATTEMPT:
                case UnitStatus::AA1SENT:
                case UnitStatus::AA2SENT:
                case UnitStatus::AA3SENT:
                case UnitStatus::DELIVERY_VERIFIED:
                    $builder->add($fieldName, new ActivityEventType(), array(
                        'mapped' => false,
                        UnitStatusEventType::OPT_STATUS => $status,
                        ActivityEventType::OPT_LOCATION => $data[0]->getRequest()->getShippingLocation()
                    ));
                    break;

                default:
                    $builder->add($fieldName, new UnitStatusEventType(), array(
                        'mapped' => false,
                        UnitStatusEventType::OPT_STATUS => $status
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
           ChangeUnitStatusType::OPT_DATA
        ));
    }
}