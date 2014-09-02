<?php

namespace Elektra\SiteBundle\Form\Field;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ParentType extends AbstractType
{

    public function getParent()
    {

        return 'entity';
    }

    public function getName()
    {

        return 'parent';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {

        parent::setDefaultOptions($resolver);

        $resolver->setDefaults(
            array(
                'show_field' => true,
            )
        );
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {

        parent::buildView($view, $form, $options);

        $view->vars['show_field'] = $options['show_field'];
    }
}