<?php

namespace Elektra\CrudBundle\Form;

use Elektra\CrudBundle\Crud\Crud;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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
    protected function getCrud()
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

        $name = $this->getCrud()->getDefinition()->getName();
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
                'css_class'    => '',
                'show_buttons' => true,
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

        parent::buildForm($builder, $options);

        $this->buildSpecificForm($builder, $options);

        if ($options['show_buttons'] == true) {
            $this->buildFormButtons($builder, $options);
        }

        //        if ($this->getCrud()->isEmbedded()) {
        //            echo 'I\'m embedded!';
        //        }
    }

    public final function buildView(FormView $view, FormInterface $form, array $options)
    {

        $classes = $this->setFormStyle();

        $classes                         = implode(' ', $classes);
        $view->vars['attr']['css_class'] = $classes;

        $this->buildSpecificView($view, $form, $options);
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    private function buildFormButtons(FormBuilderInterface $builder, array $options)
    {

        $crudAction   = $options['crud_action'];
        $entity       = $options['data'];
        $buttons      = array();
        $parentReturn = null;
        //        $parentReturn = $this->getCrud()->getParentReturn();

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
                    'link'  => $this->getCrud()->getLinker()->getFormCloseLink($entity),
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
                    'link'  => $this->getCrud()->getLinker()->getFormCloseLink($entity),
                    //                    'link'  => $parentReturn !== null ? $parentReturn : $this->getCrud()->getLink('browse'),
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
                    'link'  => $this->getCrud()->getLinker()->getFormEditLink($entity),
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
                    'link'  => $this->getCrud()->getLinker()->getFormDeleteLink($entity),
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

        $language = $this->getCrud()->getService('siteLanguage');
        $langKey  = $this->getCrud()->getLanguageKey();

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