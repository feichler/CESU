<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Form\Requests;

use Elektra\SeedBundle\Form\CRUDForm;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class RequestType
 *
 * @package Elektra\SeedBundle\Form\Requests
 *
 * @version 0.1-dev
 */
class RequestType extends CRUDForm
{

    /**
     * {@inheritdoc}
     */
    public function getName()
    {

        return "request";
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        $resolver->setDefaults(array(
            'data_class' => 'Elektra\SeedBundle\Entity\Requests\Request',
        ));
    }

     /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $requiredOptions = array(
            'required' => true,
            'constraints' => array(
                new NotBlank(array('message' => 'error.constraint.required')),
            )
        );
//        $builder->add('requestNumber', 'text');

        $builder->add('requestNumber', 'integer', array(
            'required' => true,

        ));

        $builder->add('numberOfUnitsRequested', 'integer', array(
            'required' => true,

        ));

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