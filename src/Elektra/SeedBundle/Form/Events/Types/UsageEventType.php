<?php

namespace Elektra\SeedBundle\Form\Events\Types;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Elektra\SeedBundle\Entity\Companies\Partner;
use Elektra\SeedBundle\Entity\Events\UnitUsage;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class UsageEventType extends EventType
{
    const OPT_LOCATION_CONSTRAINT = 'locationConstraint';
    const OPT_LOCATION_SCOPE = 'locationScope';
    const OPT_PARTNER = 'partner';

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return "usageEvent";
    }

    protected function buildFields(FormBuilderInterface $builder, array $options)
    {
        parent::buildFields($builder, $options);

        $locationConstraint = $options[UsageEventType::OPT_LOCATION_CONSTRAINT];
        $locationScope = $options[UsageEventType::OPT_LOCATION_SCOPE];
        /** @var Partner $partner */
        $partner = $options[UsageEventType::OPT_PARTNER];

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

    /**
     * @inheritdoc
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {

        parent::setDefaultOptions($resolver);

        $resolver->setRequired(
            array(
                UsageEventType::OPT_PARTNER
            )
        );

        $resolver->setDefaults(array(
            UsageEventType::OPT_LOCATION_CONSTRAINT => UnitUsage::LOCATION_CONSTRAINT_HIDDEN,
            UsageEventType::OPT_LOCATION_SCOPE => UnitUsage::LOCATION_SCOPE_PARTNER
        ));
    }
}