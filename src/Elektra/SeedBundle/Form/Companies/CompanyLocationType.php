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
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class CompanyLocationType
 *
 * @package Elektra\SeedBundle\Form\Companies
 *
 * @version 0.1-dev
 */
class CompanyLocationType extends CRUDForm
{

    /**
     * {@inheritdoc}
     */
    public function getName()
    {

        return 'companylocation';
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        $resolver->setDefaults(array(
            'data_class' => 'Elektra\SeedBundle\Entity\Companies\CompanyLocation',
        ));
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

        $builder->add('company', 'entity', array(
            'class' => 'Elektra\SeedBundle\Entity\Companies\Company',
            'property' => 'shortName',
            'required' => true,
            'constraints' => array(
                new NotBlank(array('message' => 'error.constraint.required')),
            )
        ));

        $builder->add('shortName', 'text', $nameOptions);
        $builder->add('name', 'text', array(
            'required' => false
        ));

        $builder->add('isPrimary', 'checkbox', array(
            'required' => false
        ));

        $builder->add('addressType', 'entity', array(
            'class' => 'Elektra\SeedBundle\Entity\Companies\AddressType',
            'property' => 'title',
            'required' => true,
            'constraints' => array(
                new NotBlank(array('message' => 'error.constraint.required'))
            )
        ));

        $builder->add("address", new AddressType());

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