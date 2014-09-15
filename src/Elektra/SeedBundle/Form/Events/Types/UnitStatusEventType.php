<?php

namespace Elektra\SeedBundle\Form\Events\Types;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UnitStatusEventType extends AbstractType
{
    const OPT_STATUS = 'status';

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return "shippingEvent";
    }

    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $this->buildFields($builder, $options);

        $builder->add('changeStatus', 'submit', array(
            // TRANSLATE
            'label' => 'Save'
        ));
    }

    protected function buildFields(FormBuilderInterface $builder, array $options)
    {
        $builder->add('unitStatus', 'hiddenEntity');
        $builder->add('eventType', 'hiddenEntity');

        $builder->add('timestamp', 'datetime', array(
//            'mapped' => false,
            // TRANSLATE
            'label' => "Timestamp",
            'input' => 'timestamp'
//            'data' => new \DateTime()
        ));

        $builder->add('comment', 'textarea', array(
            'required' => false,
//            'mapped' => false,
            // TRANSLATE
            'label' => "Comment",
            'trim' => true
        ));
    }

    /**
     * @inheritdoc
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setRequired(array(
           UnitStatusEventType::OPT_STATUS
        ));
    }

    /**
     * @inheritdoc
     */
    public function getParent()
    {
        return 'form';
    }
}