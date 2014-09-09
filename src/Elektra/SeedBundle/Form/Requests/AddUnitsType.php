<?php

namespace Elektra\SeedBundle\Form\Requests;

use Doctrine\ORM\EntityRepository;
use Elektra\CrudBundle\Form\Form as CrudForm;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AddUnitsType extends CrudForm
{

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
    /**
     * {@inheritdoc}
     */
    protected function setSpecificDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array('default_actions' => true));
    }

    /**
     * @param string $crudAction
     * @param array  $options
     */
    protected function initialiseButtons($crudAction, array $options)
    {
        $this->addFormButton('save', 'submit');

        // custom close link
        $crud = $this->getCrud();
        $definition = $crud->getDefinition();
        $navigator = $crud->getNavigator();
        $id = $crud->getData('id','addUnits');
        $link = $navigator->getLink($definition,'view',array('id' => $id));

        $this->addFormButton('cancel', 'link', array('link' => $link));

//
//        $linker = $this->getCrud()->getLinker();
////        echo $linker->getListViewLink($def->getClassEntity());
//        $navigator = $this->getCrud()->getNavigator();
//
//        echo $navigator->getLink($def,'view',array('id' => $this->getCrud()->getData('id','addUnits')));
////        $this->addFormButton('cancel', 'link', array('link' => $this->getCrud()->getLinker()->getListViewLink($)FormCloseLink($entity)));
////        echo
    }

    /**
     * {@inheritdoc}
     */
    protected function buildSpecificForm(FormBuilderInterface $builder, array $options)
    {

        $page = $this->getCrud()->getData('page', 'addUnits');
        //        echo '<pre>';
        //var_dump(array_keys($options));
        //        echo '<br />';
        //        var_dump($options['default_actions']);
        //echo get_class($options['data']);
        //        echo var_dump($options['data']->getNumberOfUnitsRequested());
        //        echo '</pre>';

        $common = $this->addFieldGroup($builder, $options, 'assign');

        $numberOptions = $this->getFieldOptions('numberOfUnitsRequested');
        $numberOptions->notMapped();
        $numberOptions->add('data', $options['data']->getNumberOfUnitsRequested());
        $common->add('numberOfUnitsRequested', 'display', $numberOptions->toArray());

        $unitOptions = $this->getFieldOptions('seedUnits', false);
        $unitOptions->add('page',$page);
        $unitOptions->add('crud', $this->getCrud());
        $unitOptions->add('definition', $this->getCrud()->getDefinition('Elektra', 'Seed', 'SeedUnits', 'SeedUnit'));
        $unitOptions->add('class', $this->getCrud()->getDefinition('Elektra', 'Seed', 'SeedUnits', 'SeedUnit')->getClassEntity());
        $unitOptions->add('property', 'title');
        $unitOptions->add('expanded', true);
        $unitOptions->add('multiple', true);
        $unitOptions->add(
            'query_builder',
            function (EntityRepository $er) {

                $builder = $er->createQueryBuilder('su');
                $builder->where($builder->expr()->isNull('su.request'));
//                echo $builder->getDQL();
                return $builder;
            }
        );

        $common->add('seedUnits', 'selectList', $unitOptions->toArray());

        //        $builder->add('seedUnits', 'entity',
        //            array(
        //                'class'    => $this->getCrud()->getDefinition('Elektra', 'Seed', 'SeedUnits', 'SeedUnit')->getClassEntity(),
        //                'property' => 'title',
        //                'expanded' => true,
        //                'multiple' => true,
        //                'query_builder' => function(EntityRepository $er)
        //                    {
        //                        return $er->createQueryBuilder('su')
        //                            ->where("su.request is null");
        //                    }
        //            )
        //        );
        //
        //        $builder->add('save', 'submit',
        //            array(
        //                'attr' =>
        //                    array(
        //                        'label' => $this->getButtonLabel('save'),
        //                        'class' => 'save',
        //                    )
        //            )
        //        );
    }
}