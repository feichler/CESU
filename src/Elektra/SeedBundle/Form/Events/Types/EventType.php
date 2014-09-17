<?php

namespace Elektra\SeedBundle\Form\Events\Types;

use Elektra\CrudBundle\Form\Field\ModalType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EventType extends ModalType
{
    const OPT_BUTTON_NAME = 'buttonName';
    const OPT_MODAL_ID = 'modalId';

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {

        return "event";
    }

    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        parent::buildForm($builder, $options);

        $this->buildFields($builder, $options);

        $buttons = array(
            $options[EventType::OPT_BUTTON_NAME] => array(
                'type'    => 'submit',
                'options' => array(
                    'label' => 'Save',
                    'attr'  => array(
                        'class' => 'btn btn-success',
                    ),
                ),
            ),
        );
        $builder->add('changeActions', 'buttonGroup', array('buttons' => $buttons));
    }

    protected function buildFields(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'timestamp',
            'datetime',
            array(
                //            'mapped' => false,
                // TRANSLATE
                'label'          => "Timestamp (UTC)",
                'input'          => 'timestamp',
                'with_seconds'   => true,
                'date_widget'    => 'single_text',
                'time_widget'    => 'single_text',
                'view_timezone'  => 'UTC',
                'model_timezone' => 'UTC',
            )
        );

        $builder->add(
            'comment',
            'textarea',
            array(
                'required' => false,
                //            'mapped' => false,
                // TRANSLATE
                'label'    => "Comment",
                'trim'     => true
            )
        );
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {

        parent::buildView($view, $form, $options);

        $view->vars[EventType::OPT_BUTTON_NAME] = $options[EventType::OPT_BUTTON_NAME];
        $view->vars[EventType::OPT_MODAL_ID] = $options[EventType::OPT_MODAL_ID];
    }

    /**
     * @inheritdoc
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {

        parent::setDefaultOptions($resolver);

        $resolver->setRequired(
            array(
                EventType::OPT_BUTTON_NAME,
                EventType::OPT_MODAL_ID
            )
        );
    }

    /**
     * @inheritdoc
     */
    public function getParent()
    {

        return 'modal';
    }
}