<?php

namespace Elektra\SeedBundle\Form\Events\Types;

use Doctrine\Common\Persistence\ObjectManager;
use Elektra\SeedBundle\Controller\EventFactory;
use Elektra\SeedBundle\Entity\Companies\Partner;
use Elektra\SeedBundle\Entity\Events\UnitUsage;
use Elektra\SeedBundle\Entity\SeedUnits\SeedUnit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ChangeUnitUsageType extends ModalFormsBaseType
{

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return "changeUnitUsage";
    }

    protected function buildFields(FormBuilderInterface $builder, array $data, ObjectManager $mgr, EventFactory $eventFactory)
    {
        $usages = $mgr->getRepository('ElektraSeedBundle:Events\UnitUsage')->findAll();

        /** @var SeedUnit $seedUnit */
        $seedUnit = $data[0];
        /** @var Partner $partner */
        $partner = $seedUnit->getRequest()->getCompany();

        foreach ($usages as $usage)
        {
            $fieldName = ChangeUnitUsageType::getModalId($usage);

            $event = $eventFactory->createUsageEvent($usage, array());

            $builder->add($fieldName, new EventType(), array(
                'data' => $event,
                'mapped' => false,
                EventType::OPT_PARTNER => $partner,
                EventType::OPT_LOCATION_CONSTRAINT => $usage->getLocationConstraint(),
                EventType::OPT_LOCATION_SCOPE => $usage->getLocationScope(),
                EventType::OPT_MODAL_ID => $fieldName,
                EventType::OPT_BUTTON_NAME => ChangeUnitUsageType::BUTTON_NAME
            ));
        }
    }


    public static function getModalId(UnitUsage $usage)
    {
        return "UsageUI_" . $usage->getAbbreviation();
    }
}