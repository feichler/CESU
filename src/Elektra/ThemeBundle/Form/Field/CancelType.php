<?php

namespace Elektra\ThemeBundle\Form\Field;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\SubmitButtonTypeInterface;

class CancelType extends AbstractType implements SubmitButtonTypeInterface
{

    /**
     * {@inheritdoc}
     */
    public function getName()
    {

        return 'cancel';
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {

        return 'button';
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {

        $view->vars['clicked']                = $form->isClicked();
        $view->vars['attr']['formnovalidate'] = true;
    }
}