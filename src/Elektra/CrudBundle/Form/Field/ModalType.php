<?php

namespace Elektra\CrudBundle\Form\Field;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ModalType extends AbstractType
{

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {

        return 'modal';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {

        parent::setDefaultOptions($resolver);

        $resolver->setDefaults(
            array( //                'label' => false,

            )
        );

//        $resolver->setRequired(
//            array(
//                //                'submit_button',
//                'header',
//            )
//        );
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        parent::buildForm($builder, $options);
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {

//        echo $view->count();
//        echo count($view->children);
//        echo '<br />';
        if($view->count()) {
        foreach ($view->children as $child) {
//            echo '1: '.$child->vars['name'] . '<br  />';
            $child->vars['modalForm'] = true;
            if($child->count()) {
            foreach ($child->children as $child2) {
//                echo '2: '.$child2->vars['name'] . '<br  />';
                $child2->vars['modalForm'] = true;
//                var_dump($child2->vars['modalForm']);
                if($child2->count()) {
                foreach ($child2->children as $child3)
                    {
//                        echo '3: '.$child3->vars['name'] . '<br  />';
                    $child3->vars['modalForm'] = true;
                        if($child3->count()){
                    foreach ($child3->children as $child4) {
//                        echo '4: '.$child4->vars['name'] . '<br  />';
                        $child4->vars['modalForm'] = true;
                    }}
                }}
            }}
        }
        }

        parent::finishView($view, $form, $options);

//        echo $view->count();
//        echo count($view->children);
//        echo '<br />';
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {

        //        foreach($view->children as $child) {
        //            echo 'AAA';
        //            echo $child->parent->vars['name'];
        //            echo $child->vars['name'];
        //            $child->vars['modalForm'] = true;
        //        }

        parent::buildView($view, $form, $options);

        $view->vars['modalForm'] = true;
        //echo count($view->children);
        //        echo $view->count();
        //        echo $form->count();
        //        foreach($form->all() as $child) {
        ////            echo get_class($child);
        //            echo $child->getName().'<br />';
        ////            $child->offsetSet('modalForm', true);
        //        }
        //        foreach($view->children as $child) {
        //            echo 'AAA';
        //            echo $child->parent->vars['name'];
        //            echo $child->vars['name'];
        //            $child->vars['modalForm'] = true;
        //        }
    }
}