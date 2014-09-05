<?php

namespace Elektra\CrudBundle\Form\Field;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RelatedListType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function getName()
    {

        return 'relatedList';
    }

    /**
     * {@inheritdoc}
     */
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
            )
        );

        $resolver->setAllowedTypes(
            array(
                'relation_parent_entity' => 'Elektra\SeedBundle\Entity\EntityInterface',
                'relation_child_type'    => 'Elektra\CrudBundle\Crud\Definition',
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        parent::buildForm($builder, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {

        $view->vars['relation_child_type']    = $options['relation_child_type'];
        $view->vars['relation_parent_entity'] = $options['relation_parent_entity'];
        $view->vars['relation_name']          = $options['relation_name'];

        parent::buildView($view, $form, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {

        parent::finishView($view, $form, $options);
    }
}