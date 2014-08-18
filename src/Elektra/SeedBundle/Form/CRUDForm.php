<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class CRUDForm
 *
 * @package Elektra\SeedBundle\Form
 *
 * @version 0.1-dev
 */
abstract class CRUDForm extends AbstractType
{

    /**
     * @var string
     */
    protected $returnLink;

    /**
     * @param string $link
     */
    public function setReturnLink($link)
    {

        $this->returnLink = $link;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {

        parent::setDefaultOptions($resolver);

        $resolver->setDefaults(
            array(
                'returnLink' => '',
            )
        );
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    protected function addFormActions(FormBuilderInterface $builder, array $options)
    {

        // TRANSLATE add translations for the form labels

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
        $backButton   = array(
            'type'    => 'cancel',
            'options' => array(
                'label'      => 'Back',
                'attr'       => array(
                    'class' => 'btn btn-primary',
                ),
                'showView'   => true,
                'showForm'   => false,
                'returnLink' => $options['returnLink'],
            ),
        );

        $builder->add(
            'actions',
            'buttonGroup',
            array(
                'buttons' => array(
                    'save'   => $saveButton,
                    'reset'  => $resetButton,
                    'cancel' => $cancelButton,
                    'back'   => $backButton,
                ),
            )
        );
    }

    /**
     * @param FormView $view
     */
    protected function setFormHorizontal(FormView $view)
    {

        $this->addFormClass($view, 'form-horizontal');
    }

    /**
     * @param FormView $view
     * @param string   $size
     * @param int      $span
     * @param int      $offset
     */
    protected function addFormWidthClasses(FormView $view, $size, $span = 12, $offset = 0)
    {

        $classSpan = 'col-' . $size . '-' . $span;
        $this->addFormClass($view, $classSpan);

        if ($offset != 0) {
            $classOffset = 'col-' . $size . '-offset-' . $offset;
            $this->addFormClass($view, $classOffset);
        }
    }

    /**
     * @param FormView $view
     * @param string   $class
     */
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