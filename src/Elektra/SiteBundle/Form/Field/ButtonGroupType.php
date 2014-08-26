<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SiteBundle\Form\Field;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Button;
use Symfony\Component\Form\ButtonBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class ButtonGroupType
 *
 * @package Elektra\SiteBundle\Form\Field
 *
 * @version 0.1-dev
 */
class ButtonGroupType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function getName()
    {

        return 'buttonGroup';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        foreach ($options['buttons'] as $name => $config) {
            // add all single buttons to this type
            $this->addButton($builder, $name, $config)->getForm();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {

        if ($form->count() == 0) {
            return;
        }

        array_map(array($this, 'validateButton'), $form->all());
    }

    /**
     * @param FormBuilderInterface $builder
     * @param string               $name
     * @param array                $config
     *
     * @return FormBuilderInterface
     */
    protected function addButton(FormBuilderInterface $builder, $name, $config)
    {

        $options = array();
        if (isset($config['options'])) {
            $options = $config['options'];
        }

        $button = $builder->add($name, $config['type'], $options);

        if (!$button instanceof ButtonBuilder) {
            // CHECK this error handling
            //            var_dump($button->getType());
            //            $builder->remove('name');
            //            echo 'INVALID TYPE ' . $config['type'] . ' at ' . $name . '<br />';
        }

        return $button;
    }

    /**
     * @param FormInterface $field
     *
     * @throws \InvalidArgumentException
     */
    protected function validateButton(FormInterface $field)
    {

        if (!$field instanceof Button) {
            throw new \InvalidArgumentException("Children of ButtonGroupType must be instances of the Button class");
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {

        $resolver->setDefaults(
            array(
                'buttons' => array(),
                'options' => array(),
                'mapped'  => false,
            )
        );
    }
}