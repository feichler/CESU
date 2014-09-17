<?php

namespace Elektra\SeedBundle\Form\Events\Types;

use Elektra\SeedBundle\Entity\Events\UnitStatus;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class InTransitType extends UnitShippingEventType
{
    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return "inTransitShippingEvent";
    }

    protected function buildFields(FormBuilderInterface $builder, array $options)
    {
        parent::buildFields($builder, $options);

        $builder->add('shippingNumber', 'text', array(
            // TRANSLATE
            'label' => 'Shipping Number'
        ));
    }

    /**
     * @inheritdoc
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setDefaults(array(
            UnitShippingEventType::OPT_STATUS => UnitStatus::IN_TRANSIT
        ));
    }

    /**
     * @inheritdoc
     */
    public function getParent()
    {

        return 'shippingEvent';
    }
}