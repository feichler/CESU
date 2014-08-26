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
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\SubmitButtonTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class LinkType
 *
 * @package Elektra\SiteBundle\Form\Field
 *
 * @version 0.1-dev
 */
class LinkType extends AbstractType implements SubmitButtonTypeInterface
{

    /**
     * {@inheritdoc}
     */
    public function getName()
    {

        return 'link';
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {

        return 'button';
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {


        $view->vars['clicked']                = $form->isClicked();

        $view->vars['link'] = $options['link'];

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {

        parent::setDefaultOptions($resolver);

        $resolver->setRequired(array(
                'link',
            ));

    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {

        parent::finishView($view, $form, $options); // TODO: Change the autogenerated stub
    }
}