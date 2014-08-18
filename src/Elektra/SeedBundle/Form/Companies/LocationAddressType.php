<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Form\Companies;

use Elektra\SeedBundle\Form\CRUDForm;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class LocationAddressType
 *
 * @package Elektra\SeedBundle\Form\Companies
 *
 * @version 0.1-dev
 */
class LocationAddressType extends CRUDForm
{

    /**
     * {@inheritdoc}
     */
    public function getName()
    {

        return 'locationaddress';
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

        $builder->add(
            'location',
            'entity',
            array(
                'class'       => 'Elektra\SeedBundle\Entity\Companies\Location',
                'property'    => 'title',
                'required'    => true,
                'constraints' => array(
                    new NotBlank(array('message' => 'error.constraint.required')),
                )
            )
        );

        $builder->add(
            'addressType',
            'entity',
            array(
                'class'       => 'Elektra\SeedBundle\Entity\Companies\AddressType',
                'property'    => 'title',
                'required'    => true,
                'constraints' => array(
                    new NotBlank(array('message' => 'error.constraint.required')),
                )
            )
        );

        $builder->add('isPrimary', 'checkbox', array('required' => false));
        $builder->add('street1', 'text', $nameOptions);
        $builder->add('street2', 'text', array('required' => false));
        $builder->add('street3', 'text', array('required' => false));
        $builder->add('postalCode', 'text', $nameOptions);
        $builder->add('city', 'text', $nameOptions);
        $builder->add('state', 'text', array('required' => false));
        $builder->add(
            'country',
            'entity',
            array(
                'class'       => 'Elektra\SeedBundle\Entity\Companies\Country',
                'property'    => 'title',
                'required'    => true,
                'constraints' => array(
                    new NotBlank(array('message' => 'error.constraint.required')),
                )
            )
        );

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