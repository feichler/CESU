<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Form\SeedUnits;

use Elektra\SeedBundle\Form\CRUDForm;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class SeedUnitType
 *
 * @package Elektra\SeedBundle\Form\SeedUnits
 *
 *          @version 0.1-dev
 */
class SeedUnitType extends CRUDForm
{

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return "seedunit";
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $serialNumberOptions = array(
            'constraints' => array(
                new NotBlank(array('message' => 'error.constraint.required')),
            )
        );
        $builder->add('serialNumber', 'text', $serialNumberOptions);

        $builder->add('model', 'entity', array(
           'class' => 'Elektra\SeedBundle\Entity\SeedUnits\SeedUnitModel',
            'property' => 'name'
        ));

        $builder->add('powerCordType', 'entity', array(
            'class' => 'Elektra\SeedBundle\Entity\SeedUnits\SeedUnitPowerCordType',
            'property' => 'name'
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