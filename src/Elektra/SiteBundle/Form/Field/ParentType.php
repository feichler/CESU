<?php

namespace Elektra\SiteBundle\Form\Field;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\ChoiceList\EntityChoiceList;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ParentType extends AbstractType
{

//    /**
//     * @var ObjectManager
//     */
//    protected $repository;

    public function __construct()
    {

//        $this->repository = $repository;
    }

    public function getParent()
    {

     return 'entity';
    }

    public function getName()
    {

        return 'parent';
    }

//    public function setDefaultOptions(OptionsResolverInterface $resolver)
//    {
//
//        parent::setDefaultOptions($resolver);
//
//$resolver->setDefaults(array(
//        'choices' => array(),
//    ));
////        $resolver->setDefaults(
////            array(
////                'transformer' => 'Elektra\SiteBundle\Form\DataTransformer\ParentToIdTransformer',
////            )
////        );
//
////        $resolver->setRequired(
////            array(
////                'parent_id_field',
////                'parent_id',
//////                'class',
//////                'data',
////            )
////        );
//    }
//
//    public function buildView(FormView $view, FormInterface $form, array $options)
//    {
//        $options['choices'] = array($options['data']->getId() => $options['data']);
//        parent::buildView($view, $form, $options); // TODO: Change the autogenerated stub
//    }
//
//    public function buildForm(FormBuilderInterface $builder, array $options)
//    {
//
//        $options['choices'] = array($options['data']->getId() => $options['data']);
//
//////        echo get_class($options['choice_list']);
////        $choiceList = $options['choice_list'];
//////$newChoiceList = new EntityChoiceList();
////        if($choiceList instanceof EntityChoiceList) {
////            foreach($choiceList->getChoices() as $choice) {
////                if($choice->getId() == $options['data']->getId()) {
////                    $options['choices'] = $choice;
////                }
//////                echo '<br />';
//////                echo get_class($choice);
////            }
////        }
//
////        $entity = $this->repository->findOneBy(array($options['parent_id_field'] => $options['parent_id']));
////        $options['data'] = $entity;
////        $options['choices'] = $entity;
//
//        parent::buildForm($builder, $options);
//    }
}