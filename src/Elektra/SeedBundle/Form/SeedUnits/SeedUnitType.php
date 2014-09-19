<?php

namespace Elektra\SeedBundle\Form\SeedUnits;

use Doctrine\Common\Persistence\ObjectManager;
use Elektra\CrudBundle\Form\Form;
use Elektra\CrudBundle\Repository\Repository;
use Elektra\SeedBundle\Entity\Events\UnitStatus;
use Elektra\SeedBundle\Entity\Events\UnitUsage;
use Elektra\SeedBundle\Entity\SeedUnits\SeedUnit;
use Elektra\SeedBundle\Form\Events\Types\ChangeUnitSalesStatusType;
use Elektra\SeedBundle\Form\Events\Types\ChangeUnitStatusType;
use Elektra\SeedBundle\Form\Events\Types\ChangeUnitUsageType;
use Elektra\SeedBundle\Form\FormsHelper;
use Elektra\SiteBundle\Site\Helper;
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

        $commonGroup->add('serialNumber', 'text', $this->getFieldOptions('serialNumber')->required()->notBlank()->toArray());

        $modelOptions = $this->getFieldOptions('model')
            ->add('class', $this->getCrud()->getDefinition('Elektra', 'Seed', 'SeedUnits', 'Model')->getClassEntity())
            ->add('property', 'title');
        $commonGroup->add('model', 'entity', $modelOptions->toArray());

        $powerCordTypeOptions = $this->getFieldOptions('powerCordType')
            ->add('class', $this->getCrud()->getDefinition('Elektra', 'Seed', 'SeedUnits', 'PowerCordType')->getClassEntity())
            ->add('property', 'title');
        $commonGroup->add('powerCordType', 'entity', $powerCordTypeOptions->toArray());

        if ($options['crud_action'] == 'add')
        {
            $locationOptions = $this->getFieldOptions('location')->add('mapped', false)
                ->add('class', $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'WarehouseLocation')->getClassEntity())
                ->add('property', 'title');
            $commonGroup->add('location', 'entity', $locationOptions->toArray());
        }

        if ($options['crud_action'] != 'add')
        {
            $locationOptions = $this->getFieldOptions('location')->add('read_only', true)
                ->add('class', $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'Location')->getClassEntity())
                ->add('property', 'title');
            $commonGroup->add('location', 'entity', $locationOptions->toArray());

            $statusOptions = $this->getFieldOptions('shippingStatus')->add('read_only', true)
                ->add('class', $this->getCrud()->getDefinition('Elektra', 'Seed', 'Events', 'UnitStatus')->getClassEntity())
                ->add('property', 'title');
            $commonGroup->add('shippingStatus', 'entity', $statusOptions->toArray());

            $this->addHistoryGroup($builder, $options);
        }
    }

    private function createModalButtonsOptions(FormBuilderInterface $builder, array $allowedStatuses, array $allowedUsages, array $allowedSalesStatuses)
    {
        $buttons = FormsHelper::createModalButtonsOptions($allowedStatuses, $allowedUsages, $allowedSalesStatuses);

        $modalButtonsOptions = $this->getFieldOptions('modalButtons');
        $modalButtonsOptions->add('menus', $buttons);

        $modalButtons = $builder->create('modalButtons', 'modalButtons', $modalButtonsOptions->toArray());

        return $modalButtons;
    }

    private function addHistoryGroup(FormBuilderInterface $builder, array $options)
    {

        $historyGroup = $this->addFieldGroup($builder, $options, 'History'); // TRANSLATE this

        /* @var $entity SeedUnit */
        $seedUnit = $options['data'];

        if ($options['crud_action'] == 'view')
        {
            /** @var ObjectManager $mgr */
            $mgr       = $this->getCrud()->getService('doctrine')->getManager();

            $seedUnits = array($seedUnit);
            $isDeliveryVerified = $seedUnit->getShippingStatus()->getInternalName() == UnitStatus::DELIVERY_VERIFIED;
            $allowedShippingStatuses = FormsHelper::getAllowedUnitStatuses($mgr, $seedUnits);
            $allowedUsages = $isDeliveryVerified ? $mgr->getRepository('ElektraSeedBundle:Events\UnitUsage')->findAll() : array();
            $allowedSalesStatuses = $isDeliveryVerified ? $mgr->getRepository('ElektraSeedBundle:Events\UnitSalesStatus')->findAll() : array();
            $historyGroup->add($this->createModalButtonsOptions($builder, $allowedShippingStatuses, $allowedUsages, $allowedSalesStatuses));

            $historyGroup->add(
                'changeStatus',
                new ChangeUnitStatusType(),
                array(
                    'mapped'                                => false,
                    ChangeUnitStatusType::OPT_DATA          => $seedUnits,
                    ChangeUnitStatusType::OPT_OBJECT_MANAGER => $mgr
                )
            );

            $historyGroup->add(
                'changeUsage',
                new ChangeUnitUsageType(),
                array(
                    'mapped'                                => false,
                    ChangeUnitUsageType::OPT_DATA          => $seedUnits,
                    ChangeUnitUsageType::OPT_OBJECT_MANAGER => $mgr
                )
            );

            $historyGroup->add(
                'changeUnitSalesStatus',
                new ChangeUnitSalesStatusType(),
                array(
                    'mapped'                                => false,
                    ChangeUnitSalesStatusType::OPT_DATA          => $seedUnits,
                    ChangeUnitSalesStatusType::OPT_OBJECT_MANAGER => $mgr
                )
            );
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