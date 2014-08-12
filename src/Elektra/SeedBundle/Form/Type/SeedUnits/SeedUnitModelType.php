<?php

namespace Elektra\SeedBundle\Form\Type\SeedUnits;

//use Elektra\ThemeBundle\Form\Field\LinkType;
use Elektra\SeedBundle\Entity\SeedUnits\SeedUnitModel;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\LegacyValidator;

class SeedUnitModelType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
//        echo get_class($builder->getData()).'<br />';
//        echo get_class($builder->getForm()->getConfig()->get).'<br />';
//        echo get_class($builder->getType()).'<br />';
        //
        //
        //        $builder->add(
        //            'buttons',
        //            'buttongroup',
        //            array(
        //                'buttons' => array(
        //                    'save'  => array('type' => 'submit', array('label' => 'Store', 'class' => 'btn btn-primary')),
        //                    'reset' => array('type' => 'reset', array('attr' => array('label' => 'Reset123', 'class' => 'btn btn-danger'))),
        //                )
        //            )
        //        );

        //        $builder->add('buttons', 'form_actions');

        //$test = $builder->create('btn-row','buttonrow');
        //        $builder->add('btns','collection');
        //        $test->add(
        //            'cancel',
        //            'cancel',
        //            array(
        //                //                'text'       => 'Cancel',
        //                //                'route'      => 'seedUnitModels_browse',
        //                //                'route_attr' => array('page' => 1),
        //                'attr' => array(
        //                    'label' => 'Cancel',
        //                    'class' => 'btn btn-danger',
        //
        //                ),
        //            )
        //        );
        //        echo get_class($test) . '<br />';
        if ($builder instanceof FormBuilder) {
        }

//        new UniqueEntity()
        $builder->add(
            'name',
            'text',
            array(
                'constraints' => array(
                    new NotBlank(array('message' => 'error.constraint.required')),
                )
            )
//            array(
//                'constraints' => array(
//                    new NotBlank(array(
//                            'message' => '"Name" is required',
//                        ))
//                ),
//            )
        );
        $builder->add('description', 'textarea', array('required' => false));
        $builder->add(
            'actions',
            'buttongroup',
            array(
                'buttons' => array(
                    'save'   => array(
                        'type'    => 'submit',
                        'options' => array(
                            'label' => 'Save',
                            'attr'  => array(
                                'class' => 'btn btn-success',
                            ),
                        ),
                    ),
                    'reset'  => array(
                        'type'    => 'reset',
                        'options' => array(
                            'label' => 'Reset Data',
                            'attr'  => array(
                                'class' => 'btn',
                            ),
                        ),
                    ),
                    'cancel' => array(
                        'type'    => 'cancel',
                        'options' => array(
                            'label' => 'Cancel',
                            'attr'  => array(
                                'class' => 'btn',
                            ),
                        ),
                    ),
                ),
            )
        );
        //        echo get_class($builder) . '<br />';
        //        $builder->add('btn-row','buttonrow');

        //        $builder->add(
        //            'cancel',
        //            'cancel',
        //            array(
        //                //                'text'       => 'Cancel',
        //                //                'route'      => 'seedUnitModels_browse',
        //                //                'route_attr' => array('page' => 1),
        //                'attr' => array(
        //                    'label' => 'Cancel',
        //                    'class' => 'btn btn-danger',
        //
        //                ),
        //            )
        //        );
        //        $test = $builder->add(
        //            'reset',
        //            'reset',
        //            array(
        //                'attr' => array(
        //                    'label' => 'Reset',
        //                    'class' => 'btn btn-warning',
        //                ),
        //            )
        //        );
        //        $test = $builder->get('reset');
        //        //        echo get_class($test);
        //
        //        $builder->add(
        //            'save',
        //            'submit',
        //            array(
        //                'label' => 'Save',
        //                'attr'  => array(
        //                    'class' => 'btn btn-success',
        //                    'first-btn'
        //                )
        //            )
        //        );
        //        $builder->add(
        //            'cancel',
        //            'submit',
        //            array(
        //                'label' => 'Cancel',
        //                'attr'  => array(
        //                    'class'          => 'btn btn-danger',
        //                    'last-btn',
        //                    'formnovalidate' => 'formnovalidate',
        //                )
        //            )
        //        );

    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {

        return 'seedunitmodel';
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {

        parent::buildView($view, $form, $options); // TODO: Change the autogenerated stub
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {

        //        $view->vars = array_merge(
        //            $view->vars,
        //            array(
        //                'attr' => array('class' => 'col-md-6 col-md-offset-3'),
        //            )
        //        );
        //        $name = $view->offsetGet('name');
        //
        //        var_dump($name->vars);
        //        var_dump(get_object_vars($view));
        $view->vars['elektra']['type']    = 'horizontal';
        $view->vars['elektra']['classes'] = 'col-lg-8 col-lg-offset-2 col-md-8 col-md-offset-2 col-sm-12';
        parent::finishView($view, $form, $options); // TODO: Change the autogenerated stub
    }
}