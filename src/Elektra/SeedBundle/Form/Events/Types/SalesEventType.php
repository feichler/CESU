<?php

namespace Elektra\SeedBundle\Form\Events\Types;

class SalesEventType extends EventType
{
    /**
     * @inheritdoc
     */
    public function getName()
    {
        return "salesEvent";
    }
}