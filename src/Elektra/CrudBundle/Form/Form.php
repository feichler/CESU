<?php

namespace Elektra\CrudBundle\Form;

use Elektra\CrudBundle\Crud\Crud;
use Elektra\CrudBundle\Crud\Definition;
use Elektra\SiteBundle\Site\Helper;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

abstract class Form extends AbstractType
{

    const BUTTON_TOP = 'top';

    const BUTTON_BOTTOM = 'bottom';

    /**
     * @var Crud
     */
    protected $crud;

    /**
     * @var array
     */
    protected $buttons;

    /**
     * @param Crud $crud
     */
    public final function __construct(Crud $crud)
    {

        $this->crud    = $crud;
        $this->buttons = array();
        Helper::setCrud($this->getCrud());
    }

    /*************************************************************************
     * common generic methods
     **************************************************************************/

    /**
     * @return Crud
     */
    public function getCrud()
    {

        return $this->crud;
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

    /*************************************************************************
     * Form Initialisation / Setup methods
     *************************************************************************/

    /**
     * @return array
     */
    protected function getUniqueEntityFields()
    {

        // NOTE override if unique entity constraint applies
        return array();
    }

    /**
     * {@inheritdoc}
     */
    public final function setDefaultOptions(OptionsResolverInterface $resolver)
    {

        parent::setDefaultOptions($resolver);

        $requiredOptions = array(
            'crud_action'
        );
        $defaults        = array(
            'css_class'       => '',
            'default_actions' => true,
        );

        // add unique field constraints if defined
        $uniqueFields = $this->getUniqueEntityFields();
        foreach ($uniqueFields as $fields) {
            if (is_string($fields)) {
                $errorMsg                  = Helper::languageAlternate('forms', 'constraints.unique', $fields);
                $constraint                = new UniqueEntity(array(
                    'fields'  => $fields,
                    'message' => $errorMsg,
                ));
                $defaults['constraints'][] = $constraint;
            } else if (is_array($fields)) {
                $path                      = $fields[0];
                $errorMsg                  = Helper::languageAlternate('forms', 'constraints.unique', $path);
                $constraint                = new UniqueEntity(array(
                    'fields'    => $fields,
                    'errorPath' => $path,
                    'message'   => $errorMsg,
                ));
                $defaults['constraints'][] = $constraint;
            }
        }

        $resolver->setRequired($requiredOptions);
        $resolver->setDefaults($defaults);

        $this->setSpecificDefaultOptions($resolver);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    protected function setSpecificDefaultOptions(OptionsResolverInterface $resolver)
    {
        // NOTE override if necessary
    }

    /*************************************************************************
     * Form & View building / generation methods
     *************************************************************************/

    /**
     * {@inheritdoc}
     */
    public final function buildForm(FormBuilderInterface $builder, array $options)
    {

        $crudAction = $options['crud_action'];
        parent::buildForm($builder, $options);

        if ($options['default_actions']) {
            $this->initialiseDefaultButtons($crudAction, $options);
        }
        $this->initialiseButtons($crudAction, $options);

        if (array_key_exists(Form::BUTTON_TOP, $this->buttons) && !empty($this->buttons[Form::BUTTON_TOP])) {
            $builder->add('actions_top', 'buttonGroup', array('buttons' => $this->buttons[Form::BUTTON_TOP], 'alignment' => 'right'));
        }

        $this->buildSpecificForm($builder, $options);

        if ($this->getCrud()->getDefinition()->isEntityAnnotable()) {
            $this->addNotesGroup($builder, $options);
        }
        if ($this->getCrud()->getDefinition()->isEntityAuditable()) {
            $this->addAuditsGroup($builder, $options);
        }

        if (array_key_exists(Form::BUTTON_BOTTOM, $this->buttons) && !empty($this->buttons[Form::BUTTON_BOTTOM])) {
            $builder->add('actions', 'buttonGroup', array('buttons' => $this->buttons[Form::BUTTON_BOTTOM]));
        }
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    protected abstract function buildSpecificForm(FormBuilderInterface $builder, array $options);

    /**
     * {@inheritdoc}
     */
    public final function buildView(FormView $view, FormInterface $form, array $options)
    {

        $classes = $this->getRenderingFormClasses();

        $view->vars['attr']['css_class'] = $classes;

        $this->buildSpecificView($view, $form, $options);
    }

    /**
     * @param FormView      $view
     * @param FormInterface $form
     * @param array         $options
     */
    protected static function buildSpecificView(FormView $view, FormInterface $form, array $options)
    {
        // NOTE override if necessary
    }

    /*************************************************************************
     * Form & View building / generation helper methods
     *************************************************************************/

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     * @param string               $groupName
     *
     * @return FormBuilderInterface
     */
    protected final function addFieldGroup(FormBuilderInterface $builder, array $options, $groupName)
    {

        static $first = true;

        $groupOptions = array(
            'inherit_data' => true,
            'label'        => $this->getGroupLabel($groupName),
        );

        if ($first == true) {
            $groupOptions['first'] = true;
            $first                 = false;
        }

        if ($options['crud_action'] == 'view') {
            $groupOptions['render_type'] = 'tab';
        } else {
            $groupOptions['render_type'] = 'fieldset';
        }

        $group = $builder->create('group_' . Helper::camelToUnderScore($groupName), 'group', $groupOptions);
        $builder->add($group);

        return $group;
    }

    /**
     * @param string $name
     * @param bool   $label
     *
     * @return Options
     */
    protected final function getFieldOptions($name, $label = true)
    {

        $options = new Options($this, $name);

        if (is_bool($label)) {
            if ($label) {
                $options['label'] = $this->getFieldLabel($name);
            } else {
                $options['label'] = false;
            }
        } else if (is_string($label)) {
            $options['label'] = $this->getFieldLabel($label);
        }

        return $options;
    }

    /*************************************************************************
     * Generic form groups
     *************************************************************************/

    protected final function addNotesGroup(FormBuilderInterface $builder, array $options)
    {

        if ($options['crud_action'] == 'view' && array_key_exists('data', $options)) { // key exists check for special case "address" - don't know why, but in this case, data is not set
            $notes             = $this->addFieldGroup($builder, $options, 'notes');
            $notesFieldOptions = $this->getFieldOptions('notes', false);
            $notesFieldOptions->add('crud', $this->getCrud());
            $notesFieldOptions->add('relation_parent_entity', $options['data']);
            $notesFieldOptions->add('relation_child_type', $this->getCrud()->getDefinition('Elektra', 'Seed', 'Notes', 'Note'));
            $notesFieldOptions->add('relation_name', 'notes');
            $notesFieldOptions->add('list_limit', 100);
            $notesFieldOptions->add('ordering_field', 'timestamp');
            $notesFieldOptions->add('ordering_direction', 'DESC');
            $notes->add('notes', 'list', $notesFieldOptions->toArray());
        }
    }

    protected final function addAuditsGroup(FormBuilderInterface $builder, array $options)
    {

        if ($options['crud_action'] == 'view' && array_key_exists('data', $options)) { // key exists check for special case "address" - don't know why, but in this case, data is not set
            $audits             = $this->addFieldGroup($builder, $options, 'audits');
            $auditsFieldOptions = $this->getFieldOptions('audits', false);
            $auditsFieldOptions->add('crud', $this->getCrud());
            $auditsFieldOptions->add('relation_parent_entity', $options['data']);
            $auditsFieldOptions->add('relation_child_type', $this->getCrud()->getDefinition('Elektra', 'Seed', 'Auditing', 'Audit'));
            $auditsFieldOptions->add('relation_name', 'audits');
            $audits->add('audits', 'list', $auditsFieldOptions->toArray());
        }
    }

    /*************************************************************************
     * Helpers for Form building
     *************************************************************************/

    protected final function addParentField($group, FormBuilderInterface $builder, array $options, Definition $definition, $fieldName, $mapped = true)
    {

        $group       = $builder->get('group_' . Helper::camelToUnderScore($group));
        $crudAction  = $options['crud_action'];
        $preSelectId = $this->getCrud()->getParentId();

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
            $parentOptions['read_only'] = true;
        }

        if (!$mapped) {
            $parentOptions['mapped']     = false;
            $parentOptions['show_field'] = false;
        }

        $fieldOptions = $this->getFieldOptions($fieldName)->required()->notBlank()->toArray();
        $fieldOptions = Helper::mergeOptions($fieldOptions, $parentOptions);

        $group->add($fieldName, 'parent', $fieldOptions);
    }

    /*************************************************************************
     * Button / Action Helpers
     *************************************************************************/

    protected final function initialiseDefaultButtons($crudAction, array $options)
    {

        $entity = $options['data'];

        // Save Button
        if ($crudAction == 'add' || $crudAction == 'edit') {
            $this->addFormButton('save', 'submit');
            $this->addFormButton('saveReturn', 'submit');
        }

        // Reset Button
        if ($crudAction == 'edit') {
            $this->addFormButton('reset', 'reset');
        }

        // Cancel Button
        if ($crudAction == 'add' || $crudAction == 'edit') {
            $this->addFormButton('cancel', 'link', array('link' => $this->getCrud()->getLinker()->getFormCloseLink($entity)));
        }

        // Close Button
        if ($crudAction == 'view') {
            $this->addFormButton('close', 'link', array('link' => $this->getCrud()->getLinker()->getFormCloseLink($entity)));
        }

        // Edit Button
        if ($crudAction == 'view') {
            $this->addFormButton('edit', 'link', array('link' => $this->getCrud()->getLinker()->getFormEditLink($entity)));
        }

        // Delete Button
        if ($crudAction == 'view') {
            $this->addFormButton('delete', 'link', array('confirm' => true, 'link' => $this->getCrud()->getLinker()->getFormDeleteLink($entity)));
        }
    }

    /**
     * @param string $crudAction
     * @param array  $options
     */
    protected function initialiseButtons($crudAction, array $options)
    {
        // NOTE: override if required -> needs to call addFormButton for correct definitions
    }

    /**
     * valid option keys:
     *      - link (for link-type buttons) -> final link
     *      - confirm -> boolean
     *      - attr -> array to add to the button attr options
     *
     * @param string $name
     * @param string $type
     * @param array  $options
     * @param string $position
     *
     * @throws \InvalidArgumentException
     */
    protected final function addFormButton($name, $type, $options = array(), $position = Form::BUTTON_BOTTOM)
    {

        if (!in_array($position, array(Form::BUTTON_TOP, Form::BUTTON_BOTTOM))) {
            throw new \InvalidArgumentException('Invalid position "' . $position . '" given');
        }

        //        $class = $this->getButtonClass($name,$position);
        //        if(isset($options['addClass'])) {
        //            $class .= ' '.$options['addClass'];
        //        }
        $attr = array(
            'class' => $this->getButtonClass($name, $position),
        );
        if (isset($options['attr']) && is_array($options['attr'])) {
            foreach ($options['attr'] as $k => $v) {
                $attr[$k] = $v;
            }
        }

        $button = array(
            'type'    => $type,
            'options' => array(
                'label' => $this->getButtonLabel($name),
                'attr'  => $attr,
            ),
        );

        if (isset($options['link'])) {
            $button['options']['link']              = $options['link'];
            $button['options']['attr']['data-href'] = $options['link'];
        }

        if (isset($options['confirm']) && $options['confirm'] == true) {
            $message = Helper::languageAlternate('view', 'confirm.' . $name);
            //            $button['options']['attr']['onclick'] = 'return confirm("' . $message . '");';
            $button['options']['attr']['data-toggle'] = 'confirmation';
            $button['options']['attr']['data-title']  = $message;
        }

        $this->buttons[$position][$name] = $button;
    }

    /**
     * Remove a button - may be used to remove single default buttons
     *
     * @param $name
     * @param $position
     */
    protected final function removeButton($name, $position = Form::BUTTON_BOTTOM)
    {

        if (array_key_exists($position, $this->buttons)) {
            if (array_key_exists($name, $this->buttons[$position])) {
                unset($this->buttons[$position][$name]);
            }
        }
    }

    /*************************************************************************
     * Label translation Helpers
     *************************************************************************/

    /**
     * @param string $type
     *
     * @return string
     */
    protected final function getButtonLabel($type)
    {

        $label = Helper::languageAlternate('forms', 'buttons.' . Helper::camelToUnderScore($type));

        return $label;
    }

    /**
     * @param string $groupName
     *
     * @return string
     */
    protected function getGroupLabel($groupName)
    {

        $label = Helper::languageAlternate('forms', 'groups.' . Helper::camelToUnderScore($groupName));

        return $label;
    }

    /**
     * @param string $fieldName
     *
     * @return string
     */
    protected function getFieldLabel($fieldName)
    {

        $label = Helper::languageAlternate('forms', 'fields.' . Helper::camelToUnderScore($fieldName));

        return $label;
    }

    /*************************************************************************
     * Rendering specific methods
     *************************************************************************/

    /**
     * @param string $type
     * @param string $position
     *
     * @return string
     */
    protected final function getButtonClass($type, $position)
    {

        $class = 'btn btn-default btn-' . Helper::camelToUnderScore($type);
        if ($position == 'top') {
            $class .= ' btn-sm';
        }

        return $class;
    }

    /**
     * @param int $lg
     * @param int $md
     * @param int $sm
     * @param int $xs
     *
     * @return string
     */
    protected function getRenderingWidth($lg = 12, $md = 12, $sm = 12, $xs = 12)
    {

        $classes = $this->getRenderingWidthClasses('lg', $lg);
        $classes = trim($classes . ' ' . $this->getRenderingWidthClasses('md', $md));
        $classes = trim($classes . ' ' . $this->getRenderingWidthClasses('sm', $sm));
        $classes = trim($classes . ' ' . $this->getRenderingWidthClasses('xs', $xs));

        return $classes;
    }

    /**
     * @param string $type
     * @param int    $cols
     *
     * @return string
     */
    private function getRenderingWidthClasses($type, $cols)
    {

        $classes = '';
        if ($cols != 12) {
            $classes = 'col-' . $type . '-' . $cols;
            $offset  = floor((12 - $cols) / 2);
            $classes .= ' col-' . $type . '-offset-' . $offset;
        }

        return $classes;
    }

    /**
     * @return string
     */
    protected function getRenderingHorizontal()
    {

        $classes = 'form-horizontal';

        return $classes;
    }

    /**
     * @return string
     */
    protected function getRenderingFormClasses()
    {

        // NOTE override for other styles
        $classes = $this->getRenderingWidth(12);
        $classes = trim($classes . ' ' . $this->getRenderingHorizontal());

        return $classes;
    }

    /*************************************************************************
     * OLD STARTS HERE!
     *************************************************************************/

    //    /**
    //     * @param int $lg
    //     * @param int $md
    //     * @param int $sm
    //     * @param int $xs
    //     *
    //     * @return array
    //     */
    //    protected function setFormWidths($lg = 12, $md = 12, $sm = 12, $xs = 12)
    //    {
    //
    //        $classes = array(
    //            'form-horizontal',
    //            'col-lg-' . $lg,
    //            'col-md-' . $md,
    //            'col-sm-' . $sm,
    //            'col-xs-' . $xs,
    //        );
    //
    //        $classes[] = $this->addColOffset('lg', $lg);
    //        $classes[] = $this->addColOffset('md', $md);
    //        $classes[] = $this->addColOffset('sm', $sm);
    //        $classes[] = $this->addColOffset('xs', $xs);
    //
    //        return $classes;
    //    }
    //
    //    /**
    //     * @return array
    //     */
    //    protected function setFormStyle()
    //    {
    //
    //        // NOTE override for other styles
    //        return $this->setFormWidths();
    //        //        return $this->setFormWidths(8, 10, 12, 12);
    //    }
    //
    //    /**
    //     * @param string $type
    //     * @param int    $cols
    //     *
    //     * @return string
    //     */
    //    private function addColOffset($type, $cols)
    //    {
    //
    //        $class = '';
    //        if ($cols != 12) {
    //            $offset = floor((12 - $cols) / 2);
    //            $class  = 'col-' . $type . '-offset-' . $offset;
    //        }
    //
    //        return $class;
    //    }

    //    /**
    //     * {@inheritdoc}
    //     */
    //    public final function setDefaultOptions1(OptionsResolverInterface $resolver)
    //    {
    //
    //        parent::setDefaultOptions($resolver);
    //
    //        // set defaults for all types
    //        $resolver->setRequired(
    //            array(
    //                'crud_action',
    //            )
    //        );
    //        $resolver->setDefaults(
    //            array(
    //                'css_class'    => '',
    //                'show_buttons' => true,
    //                //                'has_groups' => false,
    //            )
    //        );
    //        // TODO ask for unique constraint!
    //
    //        // call the function for the specific forms
    //        $this->setSpecificDefaultOptions($resolver);
    //    }

    /**
     * {@inheritdoc}
     */
    //    public final function buildForm1(FormBuilderInterface $builder, array $options)
    //    {
    //
    //        parent::buildForm($builder, $options);
    //
    //        $this->buildSpecificForm($builder, $options);
    //
    //        if ($this->getCrud()->getDefinition()->isEntityAnnotable()) {
    //            $this->addNotesGroup($builder, $options);
    //        }
    //
    //        if ($options['show_buttons'] == true) {
    //            $this->buildFormButtons($builder, $options);
    //        }
    //    }

    protected function addNotesGroup1(FormBuilderInterface $builder, array $options)
    {

        if ($options['crud_action'] == 'view' && array_key_exists('data', $options)) { // key exists check for special case "address" - don't know why, but in this case, data is not set
            $notesGroup = $this->getFieldGroup($builder, $options, 'notes');
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

    //    public final function addParentField1(FormBuilderInterface $builder, array $options, Definition $definition, $fieldName, $mapped = true)
    //    {
    //
    //        $crudAction  = $options['crud_action'];
    //        $preSelectId = $this->getCrud()->getParentId();
    //
    //        //echo $definition->getClassEntity();
    //        $parentOptions = array(
    //            'class'    => $definition->getClassEntity(),
    //            'property' => 'title',
    //        );
    //
    //        if ($crudAction == 'add') {
    //            $em                    = $this->getCrud()->getService('doctrine')->getManager();
    //            $parentRef             = $em->getReference($definition->getClassEntity(), $preSelectId);
    //            $parentOptions['data'] = $parentRef;
    //        }
    //        if ($crudAction == 'add' || $crudAction == 'edit') {
    //            // URGENT CHECK should the parent relation field be editable?
    //            $parentOptions['read_only'] = true;
    //            //            $parentOptions['disabled']  = true;
    //        }
    //
    //        if (!$mapped) {
    //            $parentOptions['mapped']     = false;
    //            $parentOptions['show_field'] = false;
    //        }
    //
    //        $options = $this->getCrud()->mergeOptions(CommonOptions::getRequiredNotBlank(), $parentOptions);
    //
    //        $builder->add($fieldName, 'parent', $options);
    //    }

    //    public final function buildView1(FormView $view, FormInterface $form, array $options)
    //    {
    //
    //        $classes = $this->getRenderingFormClasses();
    //        //        $classes = $this->setFormStyle();
    //
    //        //        $classes                         = implode(' ', $classes);
    //        $view->vars['attr']['css_class'] = $classes;
    //        //        $view->vars['has_groups'] = $options['has_groups'];
    //
    //        $this->buildSpecificView($view, $form, $options);
    //    }

    //    /**
    //     * @param FormBuilderInterface $builder
    //     * @param array                $options
    //     */
    //    private function buildFormButtons(FormBuilderInterface $builder, array $options)
    //    {
    //
    //        $crudAction   = $options['crud_action'];
    //        $entity       = $options['data'];
    //        $buttons      = array();
    //        $parentReturn = null;
    //        //        $parentReturn = $this->getCrud()->getParentReturn();
    //
    //        // SAVE Button
    //        if ($crudAction == 'add' || $crudAction == 'edit') {
    //            $buttons['save'] = array(
    //                'type'    => 'submit',
    //                'options' => array(
    //                    'label' => $this->getButtonLabel('save'),
    //                    'attr'  => array(
    //                        'class' => $this->getButtonClass('save'),
    //                    ),
    //                ),
    //            );
    //        }
    //
    //        // RESET Button
    //        if ($crudAction == 'edit') {
    //            $buttons['reset'] = array(
    //                'type'    => 'reset',
    //                'options' => array(
    //                    'label' => $this->getButtonLabel('reset'),
    //                    'attr'  => array(
    //                        'class' => $this->getButtonClass('reset'),
    //                    ),
    //                ),
    //            );
    //        }
    //
    //        // CANCEL Button
    //        if ($crudAction == 'add' || $crudAction == 'edit') {
    //            $buttons['cancel'] = array(
    //                'type'    => 'link',
    //                'options' => array(
    //                    'label' => $this->getButtonLabel('cancel'),
    //                    'attr'  => array(
    //                        'class' => $this->getButtonClass('cancel'),
    //                    ),
    //                    'link'  => $this->getCrud()->getLinker()->getFormCloseLink($entity),
    //                ),
    //            );
    //        }
    //
    //        // CLOSE Button
    //        if ($crudAction == 'view') {
    //            $buttons['close'] = array(
    //                'type'    => 'link',
    //                'options' => array(
    //                    'label' => $this->getButtonLabel('close'),
    //                    'attr'  => array(
    //                        'class' => $this->getButtonClass('close'),
    //                    ),
    //                    'link'  => $this->getCrud()->getLinker()->getFormCloseLink($entity),
    //                    //                    'link'  => $parentReturn !== null ? $parentReturn : $this->getCrud()->getLink('browse'),
    //                ),
    //            );
    //        }
    //
    //        // EDIT Button
    //        if ($crudAction == 'view') {
    //            $buttons['edit'] = array(
    //                'type'    => 'link',
    //                'options' => array(
    //                    'label' => $this->getButtonLabel('edit'),
    //                    'attr'  => array(
    //                        'class' => $this->getButtonClass('edit'),
    //                    ),
    //                    'link'  => $this->getCrud()->getLinker()->getFormEditLink($entity),
    //                ),
    //            );
    //        }
    //
    //        $language = $this->getCrud()->getService('siteLanguage');
    //        $langKey  = $this->getCrud()->getLanguageKey();
    //
    //        // DELETE Button
    //        if ($crudAction == 'view') {
    //            $buttons['delete'] = array(
    //                'type'    => 'link',
    //                'options' => array(
    //                    'label' => $this->getButtonLabel('delete'),
    //                    'attr'  => array(
    //                        'class'   => $this->getButtonClass('delete'),
    //                        'onclick' => 'return confirm("' . $language->getAlternate('view.' . $langKey . '.actions.confirm_delete', 'common.confirm_delete') . '");', // TRANSLATE this
    //                    ),
    //                    'link'  => $this->getCrud()->getLinker()->getFormDeleteLink($entity),
    //                ),
    //            );
    //        }
    //
    //        $builder->add('actions', 'buttonGroup', array('buttons' => $buttons));
    //    }

    //    /**
    //     * @param string $type
    //     *
    //     * @return string
    //     */
    //    protected function getButtonLabel($type)
    //    {
    //
    //        $language = $this->getCrud()->getService('siteLanguage');
    //        $langKey  = $this->getCrud()->getLanguageKey();
    //
    //        $label = $language->getAlternate('forms.' . $langKey . '.buttons.' . $type, 'forms.generic.buttons.' . $type);
    //
    //        return $label;
    //    }

    //    protected function getFieldLabel($fieldName)
    //    {
    //
    //        $language = $this->getCrud()->getService('siteLanguage');
    //        $langKey  = $this->getCrud()->getLanguageKey();
    //
    //        $key = 'forms.' . $langKey . '.fields.' . Helper::camelToUnderScore($fieldName);
    //
    //        return $language->getRequired($key);
    //    }

    //    protected function getGroupLabel($groupName)
    //    {
    //
    //        $language = $this->getCrud()->getService('siteLanguage');
    //        $langKey  = $this->getCrud()->getLanguageKey();
    //
    //        $key          = 'forms.' . $langKey . '.groups.' . $groupName;
    //        $alternateKey = 'forms.generic.groups.' . $groupName;
    //
    //        return $language->getAlternate($key, $alternateKey);
    //    }
    //
    //    /**
    //     * @param string $type
    //     *
    //     * @return string
    //     */
    //    private function getButtonClass($type)
    //    {
    //
    //        return 'btn btn-' . $type;
    //    }

    //    protected function getFieldGroup(FormBuilderInterface $builder, array $options, $groupName)
    //    {
    //
    //        static $counter = 0;
    //
    //        $groupOptions = array(
    //            'inherit_data' => true,
    //            'label'        => $this->getGroupLabel($groupName),
    //        );
    //
    //        if ($counter == 0) {
    //            $groupOptions['first'] = true;
    //        }
    //
    //        if ($options['crud_action'] == 'view') {
    //            $groupOptions['render_type'] = 'tab';
    //        } else {
    //            $groupOptions['render_type'] = 'fieldset';
    //        }
    //
    //        $group = $builder->create('group-' . $counter, 'group', $groupOptions);
    //        $counter++;
    //
    //        return $group;
    //    }

    /*************************************************************************
     * Abstract methods to be implemented by the specific type
     **************************************************************************/

    //    //    /**
    //    //     * @param OptionsResolverInterface $resolver
    //    //     */
    //    //    protected abstract function setSpecificDefaultOptions(OptionsResolverInterface $resolver);
    //
    //    /**
    //     * @param FormBuilderInterface $builder
    //     * @param array                $options
    //     */
    //    protected function buildSpecificForm1(FormBuilderInterface $builder, array $options){
    //
    //    }
    //
    //    /**
    //     * @param FormView      $view
    //     * @param FormInterface $form
    //     * @param array         $options
    //     */
    //    protected function buildSpecificView1(FormView $view, FormInterface $form, array $options)
    //    {
    //        // NOTE override if necessary
    //    }

    //    /*************************************************************************
    //     * Field constraint defaults
    //     **************************************************************************/
    //
    //    protected function getFieldOptions($name, $label = true)
    //    {
    //
    //        $options = new Options($this, $name);
    //        if ($label) {
    //            $options['label'] = $this->getFieldLabel($name);
    //        } else {
    //            $options['label'] = false;
    //        }
    //
    //        return $options;
    //    }

    //    protected function fieldOptionsLabel($options, $name)
    //    {
    //
    //        if (is_array($options)) {
    //            echo 'array';
    //        } else {
    //            echo 'no array';
    //        }
    //        $options['label']       = 'asdf';
    //        $options[]              = 'asdf';
    //        $test                   = array();
    //        $test['asf']            = 1;
    //        $options['constraints'] = $test;
    //        //        $options['constraints'][] = 'test';
    //        //        $options['other']['test'] = 1;
    //
    //        var_dump($options);
    //        var_dump(iterator_to_array($options));
    //    }

    //    protected function getFieldOptions($fieldName, )
    //    {
    //
    //        $optionConstraints = array();
    //
    //        if (isset($options['label']) && $options['label'] !== false) {
    //            $label            = $this->getFieldLabel($fieldName);
    //            $options['label'] = $label;
    //        }
    //
    //        return $options;
    //        //        if(in_array('not_blank',$constraints) || in_array('notBlank',$constraints)|| in_array('unique',$constraints)||in_array('unique_name'))
    //        //
    //        //        if (in_array('required', $constraints) || in_array('unique', $constraints)) {
    //        //            $options['required'] = true;
    //        //        }
    //        //
    //        //        if (in_array('optional', $constraints)) {
    //        //            $options['required'] = false;
    //        //        }
    //    }
    //
    //    protected function addOptionsRequiredNotBlank($fieldName, array $options)
    //    {
    //
    //        $this->addOptionsRequired($options);
    //        $this->addOptionsNotBlank($fieldName, $options);
    //
    //        return $options;
    //    }
    //
    //    protected function addOptionsRequired(array $options)
    //    {
    //
    //        $options['required'] = true;
    //
    //        return $options;
    //    }
    //
    //    protected function addOptionsOptional(array $options)
    //    {
    //
    //        $options['required'] = false;
    //
    //        return $options;
    //    }
    //
    //    protected function addOptionsNotBlank($fieldName, array $options)
    //    {
    //
    //        if (isset($options['constraints'])) {
    //            foreach ($options['constraints'] as $constraint) {
    //                if ($constraint instanceof NotBlank) {
    //                    // already has the constraint
    //                    return $options;
    //                }
    //            }
    //        }
    //
    //        $language = $this->getCrud()->getService('siteLanguage');
    //        $langKey  = $this->getCrud()->getLanguageKey();
    //
    //        $key          = 'forms.' . $langKey . '.constraints.not_blank.' . $fieldName;
    //        $alternateKey = 'forms.generic.constraints.not_blank.' . $fieldName;
    //
    //        $message = $language->getAlternate($key, $alternateKey);
    //
    //        $constraint               = new NotBlank(array('message' => $message));
    //        $options['constraints'][] = $constraint;
    //
    //        return $options;
    //    }
    //
    //    protected function addOptionsUniqueName($fieldName, array $options, $name)
    //    {
    //
    //        $this->addOptionsRequired($options);
    //        $this->addOptionsNotBlank($fieldName, $options);
    //
    //        return $options;
    //    }
    //
    //    protected function addOptionsUniqueEntity($fieldName, array $options, $name = 'name')
    //    {
    //
    //        if (isset($options['constraints'])) {
    //            foreach ($options['constraints'] as $constraint) {
    //                if ($constraint instanceof UniqueEntity) {
    //                    // already has the constraint
    //                    return $options;
    //                }
    //            }
    //        }
    //
    //        $language = $this->getCrud()->getService('siteLanguage');
    //        $langKey  = $this->getCrud()->getLanguageKey();
    //
    //        $key          = 'forms.' . $langKey . '.constraints.unique_entity.' . $fieldName;
    //        $alternateKey = 'forms.generic.constraints.unique_entity.' . $fieldName;
    //
    //        $message = $language->getAlternate($key, $alternateKey);
    //
    //        $constraint               = new UniqueEntity(array(
    //            'fields'  => $name,
    //            'message' => $message
    //        ));
    //        $options['constraints'][] = $constraint;
    //
    //        return $options;
    //    }
}