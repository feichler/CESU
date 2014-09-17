<?php

namespace Elektra\SeedBundle\Form\Events\Types;

use Doctrine\Common\Persistence\ObjectManager;
use Elektra\SeedBundle\Controller\EventFactory;
use Elektra\SeedBundle\Entity\Events\UnitSalesStatus;
use Elektra\SeedBundle\Entity\SeedUnits\SeedUnit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ChangeUnitSalesStatusType extends AbstractType
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
        return "changeUnitSalesStatus";
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
        $mgr = $options[ChangeUnitSalesStatusType::OPT_OBJECT_MANAGER];

        /** @var EventFactory $eventFactory */
        $eventFactory = new EventFactory($mgr);

        $this->buildFields($builder, $data, $mgr, $eventFactory);
    }

    private function buildFields(FormBuilderInterface $builder, array $data, ObjectManager $mgr, EventFactory $eventFactory)
    {
        $salesStatuses = $mgr->getRepository('ElektraSeedBundle:Events\UnitSalesStatus')->findAll();

        foreach ($salesStatuses as $salesStatus)
        {
            $fieldName = ChangeUnitSalesStatusType::getModalId($salesStatus);

            $event = $eventFactory->createSalesEvent($salesStatus, array());

            $builder->add($fieldName, new UnitSalesStatusEventType(), array(
                'data' => $event,
                'mapped' => false,
                EventType::OPT_MODAL_ID => $fieldName,
                EventType::OPT_BUTTON_NAME => ChangeUnitSalesStatusType::BUTTON_NAME,
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
            ChangeUnitSalesStatusType::OPT_DATA, ChangeUnitSalesStatusType::OPT_OBJECT_MANAGER
        ));
        $resolver->setDefaults(array(
            'label' => false
        ));
    }

    public static function getModalId(UnitSalesStatus $salesStatus)
    {
        return "SalesStatusUI_" . $salesStatus->getAbbreviation();
    }
}