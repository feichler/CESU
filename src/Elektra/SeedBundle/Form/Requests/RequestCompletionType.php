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
 * Class RequestCompletionType
 *
 * @package Elektra\SeedBundle\Form\Requests
 *
 * @version 0.1-dev
 */
class RequestCompletionType extends CRUDForm
{

    /**
     * {@inheritdoc}
     */
    public function getName()
    {

        return "requestcompletion";
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        $resolver->setDefaults(array(
            'data_class' => 'Elektra\SeedBundle\Entity\Requests\RequestCompletion',
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

        $builder->add('company','entity',
            array(
                'class'    => 'Elektra\SeedBundle\Entity\Companies\Company',
                'property' => 'title'
            )
        );

        $builder->add('requesterPerson','entity',
            array(
                'class'    => 'Elektra\SeedBundle\Entity\Companies\CompanyPerson',
                'property' => 'title'
            )
        );

        $builder->add('receiverPerson','entity',
            array(
                'class'    => 'Elektra\SeedBundle\Entity\Companies\CompanyPerson',
                'property' => 'title'
            )
        );

        $builder->add('shippingLocation','entity',
            array(
                'class'    => 'Elektra\SeedBundle\Entity\Companies\CompanyLocation',
                'property' => 'title'
            )
        );

        $builder->add('request', new RequestType());
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