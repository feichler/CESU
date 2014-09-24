<?php

namespace Elektra\SeedBundle\Form\Events\Types;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Elektra\CrudBundle\Form\Field\ModalType;
use Elektra\SeedBundle\Entity\Companies\Partner;
use Elektra\SeedBundle\Entity\Events\UnitUsage;
use Elektra\SeedBundle\Entity\SeedUnits\SeedUnit;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EventType extends ModalType
{
    const OPT_BUTTON_NAME = 'buttonName';
    const OPT_MODAL_ID = 'modalId';

    const OPT_LOCATION_CONSTRAINT = 'locationConstraint';
    const OPT_LOCATION_SCOPE = 'locationScope';
    const OPT_PARTNER = 'partner';

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {

        return "event";
    }

    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        parent::buildForm($builder, $options);

        $this->buildFields($builder, $options);

        $buttons = array(
            $options[EventType::OPT_BUTTON_NAME] => array(
                'type'    => 'submit',
                'options' => array(
                    'label' => 'Save',
                    'attr'  => array(
                        'class' => 'btn btn-success',
                    ),
                ),
            ),
        );
        $builder->add('changeActions', 'buttonGroup', array('buttons' => $buttons));
    }

    protected function buildFields(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'timestamp',
            'datetime',
            array(
                //            'mapped' => false,
                // TRANSLATE
                'label'          => "Timestamp (UTC)",
                'input'          => 'timestamp',
                'with_seconds'   => true,
                'date_widget'    => 'single_text',
                'time_widget'    => 'single_text',
                'view_timezone'  => 'UTC',
                'model_timezone' => 'UTC',
            )
        );

        $builder->add(
            'comment',
            'textarea',
            array(
                'required' => false,
                //            'mapped' => false,
                // TRANSLATE
                'label'    => "Comment",
                'trim'     => true
            )
        );

        $builder->add('usage', 'hiddenEntity');
        $builder->add('eventType', 'hiddenEntity');
        $builder->add('unitStatus', 'hiddenEntity');
        $builder->add('salesStatus', 'hiddenEntity');

        $locationConstraint = $options[EventType::OPT_LOCATION_CONSTRAINT];
        $locationScope = $options[EventType::OPT_LOCATION_SCOPE];
        /** @var Partner $partner */
        $partner = $options[EventType::OPT_PARTNER];

        // TEST CODE
        if ($locationConstraint == UnitUsage::LOCATION_CONSTRAINT_HIDDEN)
        {
            $builder->add('location', 'hiddenEntity');
        }
        else
        {
            $builder->add('location', 'entity', array(
                'required' => $locationConstraint == UnitUsage::LOCATION_CONSTRAINT_REQUIRED,
                'class' => 'Elektra\SeedBundle\Entity\Companies\CompanyLocation',
                // TRANSLATE
                'label' => 'Location',
                'property' => 'title',
                'query_builder' => function(EntityRepository $er) use($locationScope, $partner)
                    {
                        $qb = null;
                        if ($locationScope == UnitUsage::LOCATION_SCOPE_PARTNER)
                        {
                            $qb = $er->createQueryBuilder('cl');
                            $qb->where('cl.company = :partner');
                            $qb->setParameter('partner', $partner);
                        }
                        else if ($locationScope == UnitUsage::LOCATION_SCOPE_CUSTOMER)
                        {
                            $qb = $er->createQueryBuilder('cl');
                            $qb->join('ElektraSeedBundle:Companies\Customer', 'c', Join::WITH, $qb->expr()->eq('c.companyId', 'cl.company'));
                            $qb->join('c.partners', 'p');
                            $qb->where($qb->expr()->eq('p.companyId', $partner->getId()));
                        }

                        return $qb;
                    }
            ));
        }
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {

        parent::buildView($view, $form, $options);

        $view->vars[EventType::OPT_BUTTON_NAME] = $options[EventType::OPT_BUTTON_NAME];
        $view->vars[EventType::OPT_MODAL_ID] = $options[EventType::OPT_MODAL_ID];
    }

    /**
     * @inheritdoc
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {

        parent::setDefaultOptions($resolver);

        $resolver->setRequired(
            array(
                EventType::OPT_BUTTON_NAME,
                EventType::OPT_MODAL_ID,
                EventType::OPT_PARTNER
            )
        );

        $resolver->setDefaults(array(
            EventType::OPT_LOCATION_CONSTRAINT => UnitUsage::LOCATION_CONSTRAINT_HIDDEN,
            EventType::OPT_LOCATION_SCOPE => UnitUsage::LOCATION_SCOPE_PARTNER
        ));
    }

    /**
     * @inheritdoc
     */
    public function getParent()
    {

        return 'modal';
    }
}