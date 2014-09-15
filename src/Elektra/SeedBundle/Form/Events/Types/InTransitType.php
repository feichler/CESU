<?php

namespace Elektra\SeedBundle\Form\Events\Types;

use Elektra\SeedBundle\Entity\Events\UnitStatus;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class InTransitType extends UnitStatusEventType
{
    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return "inTransit";
    }

    protected function buildFields(FormBuilderInterface $builder, array $options)
    {
        parent::buildFields($builder, $options);

        $builder->add('shippingNumber', 'text', array(
            'mapped' => false,
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
           UnitStatusEventType::OPT_STATUS => UnitStatus::IN_TRANSIT
        ));
    }
}