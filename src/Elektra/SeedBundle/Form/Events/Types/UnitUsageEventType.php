<?php

namespace Elektra\SeedBundle\Form\Events\Types;

use Symfony\Component\Form\FormBuilderInterface;

class UnitUsageEventType extends EventType
{

    /**
     * @inheritdoc
     */
    public function getName()
    {

        return "usageEvent";
    }

    protected function buildFields(FormBuilderInterface $builder, array $options)
    {

        $builder->add('usage', 'hiddenEntity');
        $builder->add('eventType', 'hiddenEntity');

        parent::buildFields($builder, $options);
    }
}