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
use Symfony\Component\Validator\Constraints\NotBlank;

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

        if ($seedUnit->getRequest() == null)
            throw new \RuntimeException('Seed Unit ' . $seedUnit->getId() . ' has no request defined.');

        /** @var Partner $partner */
        $partner = $seedUnit->getRequest()->getCompany();

        foreach ($usages as $usage)
        {
            $fieldName = ChangeUnitUsageType::getModalId($usage);

            $event = $eventFactory->createUsageEvent($usage, array());

            $builder->add($fieldName, new UsageEventType(), array(
                'data' => $event,
                'mapped' => false,
                UsageEventType::OPT_PARTNER => $partner,
                UsageEventType::OPT_LOCATION_CONSTRAINT => $usage->getLocationConstraint(),
                UsageEventType::OPT_LOCATION_SCOPE => $usage->getLocationScope(),
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