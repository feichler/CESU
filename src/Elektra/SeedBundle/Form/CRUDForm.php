<?php

namespace Elektra\SeedBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;

abstract class CRUDForm extends AbstractType
{

    protected function addFormActions(FormBuilderInterface $builder)
    {

        // TODO add translations

        $saveButton   = array(
            'type'    => 'submit',
            'options' => array(
                'label' => 'Save',
                'attr'  => array(
                    'class' => 'btn btn-success',
                ),
            ),
        );
        $resetButton  = array(
            'type'    => 'reset',
            'options' => array(
                'label' => 'Reset Data',
                'attr'  => array(
                    'class' => 'btn',
                ),
            ),
        );
        $cancelButton = array(
            'type'    => 'cancel',
            'options' => array(
                'label' => 'Cancel',
                'attr'  => array(
                    'class' => 'btn',
                ),
            ),
        );

        $builder->add(
            'actions',
            'buttonGroup',
            array(
                'buttons' => array(
                    'save'   => $saveButton,
                    'reset'  => $resetButton,
                    'cancel' => $cancelButton
                ),
            )
        );
    }

    protected function setFormHorizontal(FormView $view)
    {

        $this->addFormClass($view, 'form-horizontal');
    }

    protected function addFormWidthClasses(FormView $view, $size, $span = 12, $offset = 0)
    {

        $classSpan = 'col-' . $size . '-' . $span;
        $this->addFormClass($view, $classSpan);

        if ($offset != 0) {
            $classOffset = 'col-' . $size . '-offset-' . $offset;
            $this->addFormClass($view, $classOffset);
        }
        //        $classes = '';
        //        if (!array_key_exists('elektra', $view->vars)) {
        //            $view->vars['elektra'] = array();
        //        }
    }

    private function addFormClass(FormView $view, $class)
    {

        if (!array_key_exists('elektra', $view->vars)) {
            $view->vars['elektra'] = array();
        }

        if (!array_key_exists('classes', $view->vars['elektra'])) {
            $view->vars['elektra']['classes'] = array();
        }

        $view->vars['elektra']['classes'][] = $class;
    }
}