<?php

namespace Elektra\SeedBundle\Form\Trainings;

use Elektra\SeedBundle\Form\CRUDForm;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Validator\Constraints\NotBlank;

class TrainingType extends CRUDForm
{

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return "training";
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $nameOptions = array(
            'constraints' => array(
                new NotBlank(array('message' => 'error.constraint.required')),
            )
        );
        $builder->add('name', 'text', $nameOptions);

        $locationOptions = array(
            'required' => false
        );
        $builder->add('location', 'text', $locationOptions);

        //TODO: datetime input for startedAt
        //TODO: datetime input for endedAt
        //TODO: list input for registrations
        //TODO: list input for attendances

        $this->addFormActions($builder);
    }

    /**
     * {@inheritdoc}
     */

    public function buildView(FormView $view, FormInterface $form, array $options)
    {

        parent::buildView($view, $form, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {

        $this->setFormHorizontal($view);
        $this->addFormWidthClasses($view, 'lg', 8, 2);
        $this->addFormWidthClasses($view, 'md', 8, 2);
        $this->addFormWidthClasses($view, 'sm');

        parent::finishView($view, $form, $options);
    }
}