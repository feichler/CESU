<?php

namespace Elektra\SeedBundle\Form\Events\Types;

class ShippingEventType extends EventType
{
    /**
     * @inheritdoc
     */
    public function getName()
    {
        return "shippingEvent";
    }
}