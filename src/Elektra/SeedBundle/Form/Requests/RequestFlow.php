<?php

namespace Elektra\SeedBundle\Form\Requests;

use Craue\FormFlowBundle\Form\FormFlow;
use Symfony\Component\Form\FormTypeInterface;

class RequestFlow extends FormFlow
{

    /**
     * @var FormTypeInterface
     */
    protected $formType;

    /**
     * @param FormTypeInterface $formType
     */
    public function setFormType(FormTypeInterface $formType)
    {

        $this->formType = $formType;
    }

    /**
     * {@inheritdoc}
     */
    function getName()
    {

        return 'request';
    }

    /**
     * {@inheritdoc}
     */
    protected function loadStepsConfig()
    {

        return array(
            // step 1 -> show terms & conditions
            array(
                'label' => 'Terms & Conditions',
                'type'  => $this->formType,
            ),
            // step 2 -> show objectives
            array(
                'label' => 'Objectives',
                'type'  => $this->formType,
            ),
            // step 3 -> show form itself
            array(
                'label' => 'Request Data',
                'type'  => $this->formType,
            ),
            // step 4 -> show summary
            array(
                'label' => 'Summary',
                'type'  => $this->formType,
            ),
        );
    }
}