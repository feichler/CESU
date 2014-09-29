<?php

namespace Elektra\SeedBundle\Form\SeedUnits;

use Doctrine\Common\Persistence\ObjectManager;
use Elektra\CrudBundle\Form\Form;
use Elektra\SeedBundle\Entity\SeedUnits\SeedUnit;
use Elektra\SeedBundle\Entity\SeedUnits\ShippingStatus;
use Elektra\SeedBundle\Form\Events\Types\ChangeSalesStatusType;
use Elektra\SeedBundle\Form\Events\Types\ChangeShippingStatusType;
use Elektra\SeedBundle\Form\Events\Types\ChangeUsageStatusType;
use Elektra\SeedBundle\Form\FormsHelper;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SeedUnitType extends Form
{

    /**
     * {@inheritdoc}
     */
    protected function setSpecificDefaultOptions(OptionsResolverInterface $resolver)
    {
    }

    /**
     * {@inheritdoc}
     */
    protected function buildSpecificForm(FormBuilderInterface $builder, array $options)
    {

        $commonGroup = $this->addFieldGroup($builder, $options, 'Common'); // TRANSLATE this

        $commonGroup->add('serialNumber', 'text',
            $this->getFieldOptions('serialNumber')->required()->notBlank()->toArray());

        $commonGroup->add('model', 'entity',
            $this->getFieldOptions('model')
                ->add('class', $this->getCrud()->getDefinition('Elektra', 'Seed', 'SeedUnits', 'Model')->getClassEntity())
                ->add('property', 'title')->toArray());

        $commonGroup->add('powerCordType', 'entity',
            $this->getFieldOptions('powerCordType')
                ->add('class', $this->getCrud()->getDefinition('Elektra', 'Seed', 'SeedUnits', 'PowerCordType')->getClassEntity())
                ->add('property', 'title')->toArray());

        if ($options['crud_action'] == 'add')
        {
            $commonGroup->add('location', 'entity',
                $this->getFieldOptions('location')
                    ->add('mapped', false)
                    ->add('class', $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'WarehouseLocation')->getClassEntity())
                    ->add('property', 'title')->toArray());
        }

        if ($options['crud_action'] == 'view')
        {
            $commonGroup->add('location', 'entity',
                $this->getFieldOptions('location')
                    ->add('read_only', true)
                    ->add('class', $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'Location')->getClassEntity())
                    ->add('property', 'title')->toArray());

            $commonGroup->add('shippingStatus', 'entity',
                $this->getFieldOptions('shippingStatus')
                    ->add('read_only', true)
                    ->add('class', $this->getCrud()->getDefinition('Elektra', 'Seed', 'SeedUnits', 'ShippingStatus')->getClassEntity())
                    ->add('property', 'title')->toArray());

            /* @var $seedUnit SeedUnit */
            $seedUnit = $options['data'];
            $isDeliveryVerified = $seedUnit->getShippingStatus()->getInternalName() == ShippingStatus::DELIVERY_VERIFIED;

            if ($isDeliveryVerified)
            {
                $commonGroup->add('usageStatus', 'entity',
                    $this->getFieldOptions('usageStatus')
                        ->add('read_only', true)
                        ->add('class', $this->getCrud()->getDefinition('Elektra', 'Seed', 'SeedUnits', 'UsageStatus')->getClassEntity())
                        ->add('property', 'title')->toArray());

                $commonGroup->add('salesStatus', 'entity',
                    $this->getFieldOptions('salesStatus')
                        ->add('read_only', true)
                        ->add('class', $this->getCrud()->getDefinition('Elektra', 'Seed', 'SeedUnits', 'SalesStatus')->getClassEntity())
                        ->add('property', 'title')->toArray());
            }

            $this->addHistoryGroup($builder, $options);
        }
    }

    private function addHistoryGroup(FormBuilderInterface $builder, array $options)
    {

        $historyGroup = $this->addFieldGroup($builder, $options, 'History'); // TRANSLATE this

        /* @var $seedUnit SeedUnit */
        $seedUnit = $options['data'];

        if ($options['crud_action'] == 'view')
        {
            /** @var ObjectManager $mgr */
            $mgr       = $this->getCrud()->getService('doctrine')->getManager();

            $seedUnits = array($seedUnit);
            $isDeliveryVerified = $seedUnit->getShippingStatus()->getInternalName() == ShippingStatus::DELIVERY_VERIFIED;
            $allowedShippingStatuses = FormsHelper::getAllowedShippingStatuses($mgr, $seedUnits);
            $allowedUsages = $isDeliveryVerified ? $mgr->getRepository('ElektraSeedBundle:SeedUnits\UsageStatus')->findAll() : array();
            $allowedSalesStatuses = $isDeliveryVerified ? $mgr->getRepository('ElektraSeedBundle:SeedUnits\SalesStatus')->findAll() : array();

            $buttons = FormsHelper::createModalButtonsOptions($allowedShippingStatuses, $allowedUsages, $allowedSalesStatuses);

            $historyGroup->add($builder->create('modalButtons', 'modalButtons',
                $this->getFieldOptions('modalButtons')
                    ->add('menus', $buttons)->toArray()));

            if (count($allowedShippingStatuses) > 0)
            {
                $historyGroup->add(
                    'changeShippingStatus',
                    new ChangeShippingStatusType(),
                    array(
                        'mapped'                                => false,
                        ChangeShippingStatusType::OPT_DATA          => $seedUnits,
                        ChangeShippingStatusType::OPT_OBJECT_MANAGER => $mgr
                    )
                );
            }

            if (count($allowedUsages) > 0)
            {
                $historyGroup->add(
                    'changeUsageStatus',
                    new ChangeUsageStatusType(),
                    array(
                        'mapped'                                => false,
                        ChangeUsageStatusType::OPT_DATA          => $seedUnits,
                        ChangeUsageStatusType::OPT_OBJECT_MANAGER => $mgr
                    )
                );
            }

            if (count($allowedSalesStatuses) > 0)
            {
                $historyGroup->add(
                    'changeSalesStatus',
                    new ChangeSalesStatusType(),
                    array(
                        'mapped'                                => false,
                        ChangeSalesStatusType::OPT_DATA          => $seedUnits,
                        ChangeSalesStatusType::OPT_OBJECT_MANAGER => $mgr
                    )
                );
            }
        }

        $eventsOptions = $this->getFieldOptions('events')->add('relation_parent_entity', $options['data'])
            ->add('relation_child_type', $this->getCrud()->getDefinition('Elektra', 'Seed', 'Events', 'Event'))
            ->add('ordering_field', 'timestamp')
            ->add('ordering_direction', 'DESC')
            ->add('list_limit', 100);
        $historyGroup->add('events', 'relatedList', $eventsOptions->toArray());

        return $historyGroup;
    }
}