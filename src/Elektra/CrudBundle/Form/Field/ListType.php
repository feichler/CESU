<?php

namespace Elektra\CrudBundle\Form\Field;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ListType extends AbstractType
{

    public function getName()
    {

        return 'list';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {

        parent::setDefaultOptions($resolver);

        $resolver->setRequired(
            array(
                'relation_parent_entity',
                'relation_child_type',
            )
        );

        $resolver->setOptional(
            array(
                'relation_name',
            )
        );

        $resolver->setDefaults(
            array(
                'relation_name' => null,
                'crud'          => null,
            )
        );

        $resolver->setAllowedTypes(
            array(
                'relation_parent_entity' => 'Elektra\SeedBundle\Entity\EntityInterface',
                'relation_child_type'    => 'Elektra\CrudBundle\Crud\Definition',
            )
        );
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        parent::buildForm($builder, $options);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {

        $view->vars['relation_parent_entity'] = $options['relation_parent_entity'];
        $view->vars['relation_child_type']    = $options['relation_child_type'];
        $view->vars['relation_name']          = $options['relation_name'];
        $view->vars['crud']                   = $options['crud'];
        parent::buildView($view, $form, $options);
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {

        parent::finishView($view, $form, $options);
    }
}
