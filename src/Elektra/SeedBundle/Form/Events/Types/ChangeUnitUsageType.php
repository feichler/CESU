<?php

namespace Elektra\SeedBundle\Form\Events\Types;

use Doctrine\Common\Persistence\ObjectManager;
use Elektra\SeedBundle\Controller\EventFactory;
use Elektra\SeedBundle\Entity\Events\UnitUsage;
use Elektra\SeedBundle\Entity\SeedUnits\SeedUnit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ChangeUnitUsageType extends AbstractType
{
    const OPT_DATA = 'data';
    const OPT_OBJECT_MANAGER = 'objectManager';
    const BUTTON_NAME = 'changeUsage';

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return "changeUnitUsage";
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $data = $options[ChangeUnitUsageType::OPT_DATA];

        if (is_array($data))
        {
            // TODO validate elements
        }
        else if ($data instanceof SeedUnit)
        {
            $data = array($data);
        }
        else
        {
            // TODO exception
        }

        /** @var ObjectManager $mgr */
        $mgr = $options[ChangeUnitUsageType::OPT_OBJECT_MANAGER];

        /** @var EventFactory $eventFactory */
        $eventFactory = new EventFactory($mgr);

        $this->buildFields($builder, $data, $mgr, $eventFactory);
    }

    private function buildFields(FormBuilderInterface $builder, array $data, ObjectManager $mgr, EventFactory $eventFactory)
    {
        $usages = $mgr->getRepository('ElektraSeedBundle:Events\UnitUsage')->findAll();

        foreach ($usages as $usage)
        {
            $fieldName = ChangeUnitUsageType::getModalId($usage);

            $event = $eventFactory->createUsageEvent($usage, array());

            $builder->add($fieldName, new UnitUsageEventType(), array(
                'data' => $event,
                'mapped' => false,
                EventType::OPT_MODAL_ID => $fieldName,
                EventType::OPT_BUTTON_NAME => ChangeUnitUsageType::BUTTON_NAME
            ));
        }
    }

    /**
     * @inheritdoc
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setRequired(array(
            ChangeUnitUsageType::OPT_DATA, ChangeUnitUsageType::OPT_OBJECT_MANAGER
        ));
        $resolver->setDefaults(array(
            'label' => false
        ));
    }

    public static function getModalId(UnitUsage $usage)
    {
        return "UsageUI_" . $usage->getAbbreviation();
    }
}