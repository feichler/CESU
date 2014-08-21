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
 * Class ContactInfoType
 *
 * @package Elektra\SeedBundle\Form\Companies
 *
 * @version 0.1-dev
 */
class ContactInfoType extends CRUDForm
{

    /**
     * {@inheritdoc}
     */
    public function getName()
    {

        return 'contactinfo';
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        $resolver->setDefaults(array(
            'data_class' => 'Elektra\SeedBundle\Entity\Companies\ContactInfo',
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
        $builder->add('name', 'text', $nameOptions);
        $builder->add('text', 'text', $nameOptions);

        $builder->add(
            'contactInfoType',
            'entity',
            array(
                'class'       => 'Elektra\SeedBundle\Entity\Companies\ContactInfoType',
                'property'    => 'title',
                'required'    => true,
                'constraints' => array(
                    new NotBlank(array('message' => 'error.constraint.required')),
                )
            )
        );

        $builder->add(
            'person',
            'entity',
            array(
                'class'       => 'Elektra\SeedBundle\Entity\Companies\Person',
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