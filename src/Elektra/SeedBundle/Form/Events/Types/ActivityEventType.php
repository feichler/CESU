<?php

namespace Elektra\SeedBundle\Form\Events\Types;

use Doctrine\ORM\EntityRepository;
use Elektra\CrudBundle\Form\Field\ModalType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ActivityEventType extends UnitStatusEventType
{
    const OPT_LOCATION = 'location';

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return "activity";
    }

    protected function buildFields(FormBuilderInterface $builder, array $options)
    {
        parent::buildFields($builder, $options);

        $builder->add('person', 'entity', array(
                'mapped' => false,
                'class' => 'Elektra\SeedBundle\Entity\Companies\CompanyPerson',
                // TRANSLATE
                'label' => 'Person',
                'property' => 'title',
                'query_builder' => function(EntityRepository $er) use($options)
                    {
                        $qb = $er->createQueryBuilder('p');
                        $qb->where('p.location = :location');
                        $qb->setParameter('location', $options[ActivityEventType::OPT_LOCATION]);

                        return $qb;
                    }
            ));
    }

    /**
     * @inheritdoc
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setRequired(array(
                ActivityEventType::OPT_LOCATION,
                UnitStatusEventType::OPT_STATUS
            ));
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);

        $view->vars[ActivityEventType::OPT_LOCATION] = $options[ActivityEventType::OPT_LOCATION];
    }


}