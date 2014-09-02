<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SiteBundle\Form\Field;

use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GroupType extends FormType
{

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {

        return "group";
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {

        parent::setDefaultOptions($resolver);

        $resolver->setDefaults(
            array(
                'render_type' => 'tab',
                'first'       => false,
                'constraints' => array(),
            )
        );
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {

        parent::buildView($view, $form, $options);

        $view->vars = array_replace(
            $view->vars,
            array(
                'render_type' => $options['render_type'],
                'first'       => $options['first'],
            )
        );
    }
}