<?php

namespace Elektra\SeedBundle\Form\Events\Types;

use Elektra\CrudBundle\Form\Field\ModalType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UnitSalesStatusEventType extends EventType
{

//    const OPT_SALES_STATUS = 'salesStatus';

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {

        return "salesEvent";
    }

    protected function buildFields(FormBuilderInterface $builder, array $options)
    {

        $builder->add('unitSalesStatus', 'hiddenEntity');
        $builder->add('eventType', 'hiddenEntity');

        parent::buildFields($builder, $options);
    }

//    public function buildView(FormView $view, FormInterface $form, array $options)
//    {
//
//        parent::buildView($view, $form, $options);
//
//        $view->vars[UnitSalesStatusEventType::OPT_SALES_STATUS] = $options[UnitSalesStatusEventType::OPT_SALES_STATUS];
//    }

    /**
     * @inheritdoc
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {

        parent::setDefaultOptions($resolver);
//
//        $resolver->setRequired(
//            array(
//                UnitSalesStatusEventType::OPT_SALES_STATUS
//            )
//        );
    }
}