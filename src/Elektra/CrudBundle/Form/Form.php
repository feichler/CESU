<?php

namespace Elektra\CrudBundle\Form;

use Elektra\CrudBundle\Crud\Crud;
use Elektra\CrudBundle\Crud\Definition;
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
        return $this->setFormWidths();
        //        return $this->setFormWidths(8, 10, 12, 12);
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
                //                'has_groups' => false,
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

        if ($this->getCrud()->getDefinition()->isEntityAnnotable()) {
            $this->addNotesGroup($builder, $options);
        }

        if ($options['show_buttons'] == true) {
            $this->buildFormButtons($builder, $options);
        }
    }

    protected function addNotesGroup(FormBuilderInterface $builder, array $options)
    {

        if ($options['crud_action'] == 'view' && array_key_exists('data', $options)) { // key exists check for special case "address" - don't know why, but in this case, data is not set
            $notesGroup = $this->getFieldGroup($builder, $options, 'Notes'); // TRANSLATE this
            $notesGroup->add(
                'notes',
                'list',
                array(
                    'crud'                   => $this->getCrud(),
                    'label'                  => false,
                    'relation_parent_entity' => $options['data'],
                    'relation_child_type'    => $this->getCrud()->getDefinition('Elektra', 'Seed', 'Notes', 'Note'),
                    'relation_name'          => 'notes',
                )
            );
            $builder->add($notesGroup);
        }
    }

    public final function addParentField(FormBuilderInterface $builder, array $options, Definition $definition, $fieldName, $mapped = true)
    {

        $crudAction  = $options['crud_action'];
        $preSelectId = $this->getCrud()->getParentId();

        //echo $definition->getClassEntity();
        $parentOptions = array(
            'class'    => $definition->getClassEntity(),
            'property' => 'title',
        );

        if ($crudAction == 'add') {
            $em                    = $this->getCrud()->getService('doctrine')->getManager();
            $parentRef             = $em->getReference($definition->getClassEntity(), $preSelectId);
            $parentOptions['data'] = $parentRef;
        }
        if ($crudAction == 'add' || $crudAction == 'edit') {
            // URGENT CHECK should the parent relation field be editable?
            $parentOptions['read_only'] = true;
            //            $parentOptions['disabled']  = true;
        }

        if (!$mapped) {
            $parentOptions['mapped']     = false;
            $parentOptions['show_field'] = false;
        }

        $options = $this->getCrud()->mergeOptions(CommonOptions::getRequiredNotBlank(), $parentOptions);

        $builder->add($fieldName, 'parent', $options);
    }

    public final function buildView(FormView $view, FormInterface $form, array $options)
    {

        $classes = $this->setFormStyle();

        $classes                         = implode(' ', $classes);
        $view->vars['attr']['css_class'] = $classes;
        //        $view->vars['has_groups'] = $options['has_groups'];

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

        $language = $this->getCrud()->getService('siteLanguage');
        $langKey  = $this->getCrud()->getLanguageKey();

        // DELETE Button
        if ($crudAction == 'view') {
            $buttons['delete'] = array(
                'type'    => 'link',
                'options' => array(
                    'label' => $this->getButtonLabel('delete'),
                    'attr'  => array(
                        'class'   => $this->getButtonClass('delete'),
                        'onclick' => 'return confirm("' . $language->getAlternate('view.' . $langKey . '.actions.confirm_delete', 'common.confirm_delete') . '");', // TRANSLATE this
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
    protected function getButtonLabel($type)
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

    protected function getFieldGroup(FormBuilderInterface $builder, array $options, $label)
    {

        static $counter = 0;

        $groupOptions = array(
            'inherit_data' => true,
            'label'        => $label,
        );

        if ($counter == 0) {
            $groupOptions['first'] = true;
        }

        if ($options['crud_action'] == 'view') {
            $groupOptions['render_type'] = 'tab';
        } else {
            $groupOptions['render_type'] = 'fieldset';
        }

        $group = $builder->create('group-' . $counter, 'group', $groupOptions);
        $counter++;

        return $group;
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