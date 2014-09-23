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

        $this->addFormButton('save', 'submit', array(
                'attr' => array(
//                    'data-href' => '',
                    'require-confirmation' => 'yes',
//                    'data-title' => ''
                )
            ));

        // custom close link
        $crud       = $this->getCrud();
        $definition = $crud->getDefinition();
        $navigator  = $crud->getNavigator();
        $id         = $crud->getData('id', 'addUnits');
        $link       = $navigator->getLink($definition, 'view', array('id' => $id));

        $this->addFormButton('cancel', 'link', array('link' => $link));
    }

    /**
     * {@inheritdoc}
     */
    protected function buildSpecificForm(FormBuilderInterface $builder, array $options)
    {

        $page = $this->getCrud()->getData('page', 'addUnits');
        $common = $this->addFieldGroup($builder, $options, 'assign');
        /** @var Request $request */
        $request = $options['data'];

        $noRequested   = $request->getNumberOfUnitsRequested();
        $common->add('numberOfUnitsRequested', 'display', $this->getFieldOptions('addNumberOfUnitsRequested')
            ->notMapped()
            ->add('data', $noRequested)->toArray());

        $noRequired = max(0, $noRequested - count($request->getSeedUnits()));
        $common->add('numberOfUnitsRequired', 'display', $this->getFieldOptions('addNumberOfUnitsRequired')
            ->notMapped()
            ->add('data', $noRequired)->toArray());

        $definition = $this->getCrud()->getDefinition('Elektra', 'Seed', 'SeedUnits', 'SeedUnit');
        $unitOptions = $this->getFieldOptions('seedUnits', false)
            ->add('page', $page)
            ->add('crud', $this->getCrud())
            ->add('definition', $definition)
            ->add('class', $definition->getClassEntity())
            ->add('property', 'title')
            ->add('expanded', true)
            ->add('multiple', true)
            ->add('required', false)
            ->add(
                'query_builder',
                function (EntityRepository $er) {

                    // NOTE: not really used
                    $builder = $er->createQueryBuilder('su');
                    $builder->where($builder->expr()->isNull('su.request'));

                    return $builder;
                }
            );

        $common->add('seedUnits', 'selectList', $unitOptions->toArray());
    }
}