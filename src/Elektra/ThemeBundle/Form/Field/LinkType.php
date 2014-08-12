<?php

namespace Elektra\ThemeBundle\Form\Field;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ButtonTypeInterface;
use Symfony\Component\Form\ClickableInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormExtensionInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\SubmitButtonTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LinkType extends AbstractType
{

    /**
     * Returns whether this element was clicked.
     *
     * @return bool Whether this element was clicked.
     */
    public function isClicked()
    {

        return false;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {

        $options = parent::setDefaultOptions($resolver);
        //        $resolver->
        $resolver->setDefaults(
            array(
                'text'       => null,
                'route'      => null,
                'route_attr' => array(),
                'target'     => null,
            )
        );

        //        $resolver->setRequired(array('text'));

        return $options;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {

        return 'link';
    }

    public function getAsdf()
    {

        return 'xyz';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        //$builder->setAttribute('route',$options['route']);
        //        $builder->setAttribute('route_attr',$options['route_attr']);
        //        $builder->setAttribute('target',$options['target']);

        //        var_dump($options['asdf']);
        //        $builder->setAttribute('asdf', $options['asdf']);
        parent::buildForm($builder, $options); // TODO: Change the autogenerated stub
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {

        $view->vars = array_replace(
            $view->vars,
            array(
                'text'       => $options['text'],
                'route'      => $options['route'],
                'route_attr' => $options['route_attr'],
                'target'     => $options['target'],
            )
        );
        if ($options['target'] != null) {
            $view->vars['attr']['target'] = $options['target'];
        }

        //        $view->vars['route']      = $options['route'];
        //        $view->vars['route_attr'] = $options['route_attr'];
        //        $view->vars['target']     = $options['target'];
        //        //        $view->vars['asdf'] = $options['asdf'];
        //        //        $data               = $form->getViewData();
        //        //        var_dump($data);
        //        //        echo get_class($data);
        //        //        $view->vars['asdf']=$form->getViewData();
        //        //        $options['asdf'] = '12345678';
        //        parent::buildView($view, $form, $options); // TODO: Change the autogenerated stub

    }



    //    public function getParent()
    //    {
    //    }
}