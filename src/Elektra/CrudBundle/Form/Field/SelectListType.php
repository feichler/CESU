<?php

namespace Elektra\CrudBundle\Form\Field;

use Doctrine\Common\Persistence\ManagerRegistry;
use Elektra\CrudBundle\Crud\Crud;
use Elektra\CrudBundle\Crud\Definition;
use Symfony\Bridge\Doctrine\Form\ChoiceList\EntityChoiceList;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Exception\RuntimeException;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class SelectListType extends EntityType
{

    public function __construct(ManagerRegistry $registry)
    {

        parent::__construct($registry, PropertyAccess::createPropertyAccessor());
    }

    public function getName()
    {

        return 'selectList';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {

        parent::setDefaultOptions($resolver);

        $resolver->setDefaults(
            array(
                'renderType' => 'selectList',
            )
        );
        $resolver->setRequired(
            array(
                'crud',
                'definition',
                'page',
            )
        );
        $resolver->setAllowedTypes(
            array(
                'crud'       => 'Elektra\CrudBundle\Crud\Crud',
                'definition' => 'Elektra\CrudBundle\Crud\Definition'
            )
        );
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {

        //        foreach (array_keys($options) as $key) {
        //        }
        //        $choices    = $options['choices'];
        $choiceList = $options['choice_list'];
        //        echo count($choices) . '<br />';
        //        echo count($choiceList) . '<br />';
        //        if($choiceList instanceof EntityChoiceList) {
        //            echo count($choiceList->getChoices()).'<br />';
        //            foreach($choiceList->getChoices() as $choice) {
        //                echo get_class($choice);
        //                break;
        //            }
        //        }
        //        var_dump($choiceList);

        $crud       = $options['crud'];
        $definition = $options['definition'];
        if ($crud instanceof Crud) {

//            $parentDef = $crud->getDefinition();

//            echo $parentDef->getName();

            $crud->setDefinition($definition);
//            $crud->setData('render', 'form');
//            $crud->setData('render', $parentDef->getName());
//            $crud->setData('parentDefinition', $parentDef);
            $tableClass = $definition->getClassTable();
            $table      = new $tableClass($crud);

            $table->load($options['page']);
            //            $crud->setData('render',null);
        }
        $view->vars['definition'] = $definition;
        $view->vars['table']      = $table;
        $view->vars['renderType'] = $options['renderType'];
    }
}