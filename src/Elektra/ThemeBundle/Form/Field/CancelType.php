<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\ThemeBundle\Form\Field;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\SubmitButtonTypeInterface;

/**
 * Class CancelType
 *
 * @package Elektra\ThemeBundle\Form\Field
 *
 * @version 0.1-dev
 */
class CancelType extends AbstractType implements SubmitButtonTypeInterface
{

    /**
     * {@inheritdoc}
     */
    public function getName()
    {

        return 'cancel';
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
        $view->vars['attr']['formnovalidate'] = true;
    }
}