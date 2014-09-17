<?php

namespace Elektra\SeedBundle\Form\Events\Types;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UnitShippingEventType extends EventType
{

//    const OPT_STATUS = 'status';

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {

        return "shippingEvent";
    }

    protected function buildFields(FormBuilderInterface $builder, array $options)
    {

        $builder->add('unitStatus', 'hiddenEntity');
        $builder->add('eventType', 'hiddenEntity');

        parent::buildFields($builder, $options);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {

        parent::buildView($view, $form, $options);

//        $view->vars[UnitShippingEventType::OPT_STATUS] = $options[UnitShippingEventType::OPT_STATUS];
    }

    /**
     * @inheritdoc
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {

        parent::setDefaultOptions($resolver);

//        $resolver->setRequired(
//            array(
//                UnitShippingEventType::OPT_STATUS
//            )
//        );
    }
}