<?php

namespace Elektra\CrudBundle\Form\Field;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ModalButtonsType extends AbstractType
{

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {

        return 'modalButtons';
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {

        parent::buildView($view, $form, $options);

        $view->vars['alignment'] = $options['alignment'];
        $view->vars['menus']     = $options['menus'];
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {

        parent::setDefaultOptions($resolver);

        $resolver->setDefaults(
            array(
                'menus'     => array(),
                'alignment' => 'center',
                'mapped'    => false,
            )
        );
    }
}