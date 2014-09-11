<?php

namespace Elektra\SeedBundle\Form\Requests;

use Doctrine\ORM\EntityRepository;
use Elektra\CrudBundle\Form\Form as CrudForm;
use Elektra\SeedBundle\Entity\Requests\Request;
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

    }

    /**
     * {@inheritdoc}
     */
    protected function buildSpecificForm(FormBuilderInterface $builder, array $options)
    {

        $page = $this->getCrud()->getData('page', 'addUnits');

        $common = $this->addFieldGroup($builder, $options, 'assign');

        $noRequested = $options['data']->getNumberOfUnitsRequested();
        $numberOptions = $this->getFieldOptions('numberOfUnitsRequested');
        $numberOptions->notMapped();
        $numberOptions->add('data', $noRequested);
        $common->add('numberOfUnitsRequested', 'display', $numberOptions->toArray());

        $request = $options['data'];
//        echo get_class($request);
        if($request instanceof Request) {
            $noSelected = count($request->getSeedUnits());
            $noRequired = $noRequested-$noSelected;
            $requiredOptions = $this->getFieldOptions('numberOfUnitsRequired');
            $requiredOptions->notMapped();
            $numberOptions->add('data',$noRequired);
            $common->add('numberOfUnitsRequired','display',$requiredOptions->toArray());
        }


        $unitOptions = $this->getFieldOptions('seedUnits', false);
//        $unitOptions->notMapped();
        $unitOptions->add('page',$page);
        $unitOptions->add('crud', $this->getCrud());
        $unitOptions->add('definition', $this->getCrud()->getDefinition('Elektra', 'Seed', 'SeedUnits', 'SeedUnit'));
        $unitOptions->add('class', $this->getCrud()->getDefinition('Elektra', 'Seed', 'SeedUnits', 'SeedUnit')->getClassEntity());
        $unitOptions->add('property', 'title');
        $unitOptions->add('expanded', true);
        $unitOptions->add('multiple', true);
        $unitOptions->add('required', false);
        $unitOptions->add(
            'query_builder',
            function (EntityRepository $er) {
                // NOTE: not really used
                $builder = $er->createQueryBuilder('su');
                $builder->where($builder->expr()->isNull('su.request'));
                return $builder;
            }
        );

        $common->add('seedUnits', 'selectList', $unitOptions->toArray());

//        $unitOptions = $this->getFieldOptions('seedUnits', false);
//        $uDefinition = $this->getCrud()->getDefinition('Elektra', 'Seed', 'SeedUnits', 'SeedUnit');
//        $this->getCrud()->setParent($options['data'], $this->getCrud()->getLinker()->getActiveRoute(), null);
//        $this->getCrud()->setDefinition($uDefinition);
//        $unitOptions->add('multiple',true);
//        $unitOptions->add('class', $uDefinition->getClassEntity());
//        $unitOptions->add('crud', $this->getCrud());
//        $unitOptions->add('child', $uDefinition);
//        $unitOptions->add('parent', $this->getCrud()->getParentDefinition());
//        $unitOptions->add(
//            'query_builder',
//            function (EntityRepository $repository) use ($options) {
//
//                $builder = $repository->createQueryBuilder('u');
//                $builder->where($builder->expr()->isNull('u.request'));
////                $builder->setParameter('request', $options['data']);
//
//                return $builder;
//            }
//        );
//
//        $common->add('seedUnits', 'entityTable', $unitOptions->toArray());

    }
}