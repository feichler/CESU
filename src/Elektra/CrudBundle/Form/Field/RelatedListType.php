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

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {

        parent::setDefaultOptions($resolver);

        //        $resolver->setDefaults(
        //            array(
        //                'definition' => null,
        //            )
        //        );

        $resolver->setRequired(
            array(
//                'relation_parent_type',
                'relation_parent_entity',
                'relation_child_type',
                //                'relation_child_field',
                //                'parentDefinition',
                //                'embeddedDefinition',
                //                'parentEntity',
                //                'relationName',
            )
        );

        $resolver->setAllowedTypes(
            array(
//                'relation_parent_type'   => 'Elektra\CrudBundle\Crud\Definition',
                'relation_parent_entity' => 'Elektra\SeedBundle\Entity\EntityInterface',
                'relation_child_type'    => 'Elektra\CrudBundle\Crud\Definition',
                //                'relation_child_field'   => 'string',
            )
        );
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        parent::buildForm($builder, $options);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {

//        $view->vars['relation_parent_type']   = $options['relation_parent_type'];
        $view->vars['relation_child_type']    = $options['relation_child_type'];
        $view->vars['relation_parent_entity'] = $options['relation_parent_entity'];
        //        $view->vars['relation_child_field']   = $options['relation_child_field'];

        parent::buildView($view, $form, $options);
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {

        parent::finishView($view, $form, $options);
    }
}