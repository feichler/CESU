<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SiteBundle\Form\Field;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType as BaseType;

/**
 * Class SubmitType
 *
 * @package Elektra\SiteBundle\Form\Field
 *
 * @version 0.1-dev
 */
class SubmitType extends BaseType
{

    /**
     * {@inheritdoc}
     */

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {

        parent::setDefaultOptions($resolver);

        $resolver->setDefaults(
            array(
                'showForm' => true,
                'showView' => false,
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {

        parent::buildView($view, $form, $options);

        $view->vars['showForm'] = $options['showForm'];
        $view->vars['showView'] = $options['showView'];
    }
}
