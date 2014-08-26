<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\CrudBundle\Form;

use Elektra\CrudBundle\Controller\Controller;
use Elektra\CrudBundle\Crud\Crud;
use Elektra\CrudBundle\Definition\Definition;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class CRUDForm
 *
 * @package Elektra\SeedBundle\Form
 *
 * @version 0.1-dev
 */
abstract class Form extends AbstractType
{

    /**
     * @var Crud
     */
    protected $crud;

    /**
     * @param Crud $crud
     */
    public final function __construct(Crud $crud)
    {

        $this->crud = $crud;
    }

    /**
     * @return Crud
     */
    public function getCrud()
    {

        return $this->crud;
    }

    /**
     * @param int $lg
     * @param int $md
     * @param int $sm
     * @param int $xs
     *
     * @return array
     */
    protected function setFormWidths($lg = 12, $md = 12, $sm = 12, $xs = 12)
    {

        $classes = array(
            'form-horizontal',
            'col-lg-' . $lg,
            'col-md-' . $md,
            'col-sm-' . $sm,
            'col-xs-' . $xs,
        );

        $classes[] = $this->addColOffset('lg', $lg);
        $classes[] = $this->addColOffset('md', $md);
        $classes[] = $this->addColOffset('sm', $sm);
        $classes[] = $this->addColOffset('xs', $xs);

        return $classes;
    }

    /**
     * @return array
     */
    protected function setFormStyle()
    {

        // NOTE override for other styles
        return $this->setFormWidths(8, 10, 12, 12);
    }

    /**
     * @param string $type
     * @param int    $cols
     *
     * @return string
     */
    private function addColOffset($type, $cols)
    {

        $class = '';
        if ($cols != 12) {
            $offset = floor((12 - $cols) / 2);
            $class  = 'col-' . $type . '-offset-' . $offset;
        }

        return $class;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {

        // NOTE override if necessary

        $name = $this->crud->getDefinition()->getName();
        $name = strtolower($name);

        return $name;
    }

    /**
     * {@inheritdoc}
     */
    public final function setDefaultOptions(OptionsResolverInterface $resolver)
    {

        parent::setDefaultOptions($resolver);

        // set defaults for all types
        $resolver->setRequired(
            array(
                'crud_action',
            )
        );
        $resolver->setDefaults(
            array(
                'css_class'      => '',
            )
        );

        // call the function for the specific forms
        $this->setSpecificDefaultOptions($resolver);
    }

    /**
     * {@inheritdoc}
     */
    public final function buildForm(FormBuilderInterface $builder, array $options)
    {

        //        var_dump(array_key_exists('data', $options));

        //        $crudAction = $options['crud_action'];
        //        $returnLink = $options['return_link'];

        parent::buildForm($builder, $options);

        $this->buildSpecificForm($builder, $options);

        $this->buildFormButtons($builder, $options);
    }

    public final function buildView(FormView $view, FormInterface $form, array $options)
    {

        $classes = $this->setFormStyle();

        $classes                     = implode(' ', $classes);
        $view->vars['attr']['css_class'] = $classes;

        $this->buildSpecificView($view, $form, $options);
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    private function buildFormButtons(FormBuilderInterface $builder, array $options)
    {

        $crudAction = $options['crud_action'];
        $entity     = $options['data'];
        $buttons    = array();

        $return = $this->getCrud()->get('viewUrl');
        //echo '<br /><br />';
        //        echo 'RETURN: ';
        //        var_dump($return);
        //        echo '<br /><br />';
        // SAVE Button
        if ($crudAction == 'add' || $crudAction == 'edit') {
            $buttons['save'] = array(
                'type'    => 'submit',
                'options' => array(
                    'label' => $this->getButtonLabel('save'),
                    'attr'  => array(
                        'class' => $this->getButtonClass('save'),
                    ),
                ),
            );
        }

        // RESET Button
        if ($crudAction == 'edit') {
            $buttons['reset'] = array(
                'type'    => 'reset',
                'options' => array(
                    'label' => $this->getButtonLabel('reset'),
                    'attr'  => array(
                        'class' => $this->getButtonClass('reset'),
                    ),
                ),
            );
        }

        // CANCEL Button
        if ($crudAction == 'add' || $crudAction == 'edit') {
            $buttons['cancel'] = array(
                'type'    => 'link',
                'options' => array(
                    'label' => $this->getButtonLabel('cancel'),
                    'attr'  => array(
                        'class' => $this->getButtonClass('cancel'),
                    ),
                    'link'  => $this->crud->getActiveBrowseLink(),
                ),
            );
        }

        // CLOSE Button
        if ($crudAction == 'view') {
            $buttons['close'] = array(
                'type'    => 'link',
                'options' => array(
                    'label' => $this->getButtonLabel('close'),
                    'attr'  => array(
                        'class' => $this->getButtonClass('close'),
                    ),
                    'link'  => $this->crud->getActiveBrowseLink(),
                ),
            );
        }

        // EDIT Button
        if ($crudAction == 'view') {
            $buttons['edit'] = array(
                'type'    => 'link',
                'options' => array(
                    'label' => $this->getButtonLabel('edit'),
                    'attr'  => array(
                        'class' => $this->getButtonClass('edit'),
                    ),
                    'link'  => $this->crud->getEditLink($entity),
                ),
            );
        }

        // DELETE Button
        if ($crudAction == 'view') {
            $buttons['delete'] = array(
                'type'    => 'link',
                'options' => array(
                    'label' => $this->getButtonLabel('delete'),
                    'attr'  => array(
                        'class' => $this->getButtonClass('delete'),
                    ),
                    'link'  => $this->crud->getDeleteLink($entity),
                ),
            );
        }

        $builder->add('actions', 'buttonGroup', array('buttons' => $buttons));
    }

    /**
     * @param string $type
     *
     * @return string
     */
    private function getButtonLabel($type)
    {

        $language = $this->crud->getService('siteLanguage');
        $langKey  = $this->crud->getLangKey();

        $label = $language->getAlternate('form.' . $langKey . '.buttons.' . $type, 'form.buttons.' . $type);

        return $label;
    }

    /**
     * @param string $type
     *
     * @return string
     */
    private function getButtonClass($type)
    {

        return 'btn btn-' . $type;
    }

    /*************************************************************************
     * Abstract methods to be implemented by the specific type
     **************************************************************************/

    /**
     * @param OptionsResolverInterface $resolver
     */
    protected abstract function setSpecificDefaultOptions(OptionsResolverInterface $resolver);

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    protected abstract function buildSpecificForm(FormBuilderInterface $builder, array $options);

    /**
     * @param FormView      $view
     * @param FormInterface $form
     * @param array         $options
     */
    protected function buildSpecificView(FormView $view, FormInterface $form, array $options)
    {
        // NOTE override if necessary
    }
}