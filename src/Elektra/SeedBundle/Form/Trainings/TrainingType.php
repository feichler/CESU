<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Form\Trainings;

use Elektra\SeedBundle\Form\CRUDForm;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class TrainingType
 *
 * @package Elektra\SeedBundle\Form\Trainings
 *
 * @version 0.1-dev
 */
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

        $dateOptions = array(
            'required' => true,
            'input'    => 'timestamp'
        );

        $builder->add('startedAt', 'datetime', $dateOptions);
        $builder->add('endedAt', 'datetime', $dateOptions);
        //TODO: list input for registrations
        //TODO: list input for attendances

        $this->addFormActions($builder, $options);
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