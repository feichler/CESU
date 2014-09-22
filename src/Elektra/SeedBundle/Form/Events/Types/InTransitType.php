<?php

namespace Elektra\SeedBundle\Form\Events\Types;

use Symfony\Component\Form\FormBuilderInterface;

class InTransitType extends EventType
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
}