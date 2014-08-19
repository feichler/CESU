<?php

namespace Elektra\SeedBundle\Form\Requests;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\EqualTo;

class RequestType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function getName()
    {

        return 'request';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        switch ($options['flow_step']) {
            case '1':
                $this->buildTnCForm($builder, $options);
                break;
            case '2':
                $this->buildObjectivesForm($builder, $options);
                break;
            case '3':
                $this->buildDataForm($builder, $options);
                break;
            case '4':
                $this->buildSummaryForm($builder, $options);
                break;
        }
    }

    protected function buildTnCForm(FormBuilderInterface $builder, array $options)
    {

        //        $builder->add('test1', 'text', array('mapped' => false));
        $builder->add(
            'agreeTerms',
            'checkbox',
            array(
                'mapped'            => false,
                'required'          => false,
                'validation_groups' => 'flow_request_step1',
                'constraints'       => array(
                    new EqualTo(array('value' => true, 'message' => 'Must agree toc', 'groups' => 'flow_request_step1')),
                )
            )
        );
    }

    protected function buildObjectivesForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('requestNumber', 'text', array('read_only' => true));
        $builder->add(
            'agreeObjectives',
            'checkbox',
            array(
                'mapped'            => false,
                'required'          => false,
                'validation_groups' => 'flow_request_step1',
                'constraints'       => array(
                    new EqualTo(array('value' => true, 'message' => 'Must agree objectives', 'groups' => 'flow_request_step1')),
                )
            )
        );
    }

    protected function buildDataForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('requestNumber', 'text', array('read_only' => true));
        $builder->add('test3', 'text', array('mapped' => false));
    }

    protected function buildSummaryForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('test4', 'text', array('mapped' => false));
    }
}