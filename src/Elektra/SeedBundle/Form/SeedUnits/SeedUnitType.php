<?php

namespace Elektra\SeedBundle\Form\SeedUnits;

use Elektra\CrudBundle\Form\Form;
use Elektra\CrudBundle\Repository\Repository;
use Elektra\SeedBundle\Entity\Events\UnitStatus;
use Elektra\SeedBundle\Entity\Events\UnitUsage;
use Elektra\SeedBundle\Entity\SeedUnits\SeedUnit;
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

        $modelOptions = $this->getFieldOptions('model')->add('class', $this->getCrud()->getDefinition('Elektra', 'Seed', 'SeedUnits', 'Model')->getClassEntity())->add('property', 'title');
        $commonGroup->add('model', 'entity', $modelOptions->toArray());

        $powerCordTypeOptions = $this->getFieldOptions('powerCordType')->add('class', $this->getCrud()->getDefinition('Elektra', 'Seed', 'SeedUnits', 'PowerCordType')->getClassEntity())->add(
            'property',
            'title'
        );
        $commonGroup->add('powerCordType', 'entity', $powerCordTypeOptions->toArray());

        if ($options['crud_action'] == 'add') {
            $locationOptions = $this->getFieldOptions('location')->add('mapped', false)->add(
                'class',
                $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'WarehouseLocation')->getClassEntity()
            )->add('property', 'title');
            $commonGroup->add('location', 'entity', $locationOptions->toArray());
        }

        if ($options['crud_action'] != 'add') {
            $locationOptions = $this->getFieldOptions('location')->add('read_only', true)->add(
                'class',
                $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'Location')->getClassEntity()
            )->add('property', 'title');
            $commonGroup->add('location', 'entity', $locationOptions->toArray());

            $statusOptions = $this->getFieldOptions('unitStatus')->add('read_only', true)->add(
                'class',
                $this->getCrud()->getDefinition('Elektra', 'Seed', 'Events', 'UnitStatus')->getClassEntity()
            )->add('property', 'title');
            $commonGroup->add('unitStatus', 'entity', $statusOptions->toArray());

            $this->addHistoryGroup($builder, $options);
        }

        /* @var $entity SeedUnit */
        $entity = $options['data'];

        if($entity->getUnitStatus() !== null) {
        if ($entity->getUnitStatus()->getInternalName() == UnitStatus::DELIVERY_VERIFIED) {
            $usageOptions = $this->getFieldOptions('unitUsage')->add('class', $this->getCrud()->getDefinition('Elektra', 'Seed', 'Events', 'UnitUsage')->getClassEntity())->add('property', 'title');
            $commonGroup->add('unitUsage', 'entity', $usageOptions->toArray());
        }
        }
    }

    private function addHistoryGroup(FormBuilderInterface $builder, array $options)
    {

        $historyGroup = $this->addFieldGroup($builder, $options, 'History'); // TRANSLATE this

        $eventsOptions = $this->getFieldOptions('events')->add('relation_parent_entity', $options['data'])->add(
            'relation_child_type',
            $this->getCrud()->getDefinition('Elektra', 'Seed', 'Events', 'Event')
        )->add('ordering_field', 'timestamp')->add('ordering_direction', 'DESC')->add('list_limit', 100);
        $historyGroup->add('events', 'relatedList', $eventsOptions->toArray());

        return $historyGroup;
    }

    /**
     * @inheritdoc
     */
    protected function initialiseButtons($crudAction, array $options)
    {

        $linker = $this->getCrud()->getLinker();
        $route  = $linker->getActiveRoute();

        if ($route == 'request.seedUnit.view') {
            $this->removeButton('edit');
            $this->removeButton('delete');
        }

        /* @var $entity SeedUnit */
        $entity = $options['data'];
        /* @var $unitStatus UnitStatus */
        $unitStatus = $entity->getUnitStatus();


        if($unitStatus !== null) {
        if ($unitStatus->getInternalName() == UnitStatus::DELIVERY_VERIFIED) {
            $buttons = $this->initialiseUsageButtons($entity);
        } else {
            $buttons = $this->initialiseShippingButtons($entity, $unitStatus);
        }

        foreach ($buttons as $key => $button) {
            $this->addFormButton($key, 'link', $button, Form::BUTTON_TOP);
        }
        }
    }

    /**
     * @param SeedUnit $seedUnit
     *
     * @return array
     */
    private function initialiseUsageButtons(SeedUnit $seedUnit)
    {

        $buttons = array();
        /* @var $repo Repository */
        $repo = $this->getCrud()->getService('doctrine')->getRepository(
            $this->getCrud()->getNavigator()->getDefinition('Elektra', 'Seed', 'Events', 'UnitUsage')->getClassRepository()
        );

        $usages = $repo->findAll();

        $currentUsage = $seedUnit->getUnitUsage();
        $siteLanguage = $this->getCrud()->getService('siteLanguage');
        /* @var $usage UnitUsage */
        foreach ($usages as $usage) {
            if ($currentUsage != null && $usage->getId() == $currentUsage->getId()) {
                continue;
            }

            $langKey        = 'forms.seed_units.seed_unit.buttons.' . Helper::camelToUnderScore($usage->getAbbreviation());
            $langIdentifier = 'forms.seed_units.seed_unit.buttons.usage';
            $siteLanguage->add($langKey, $langIdentifier, array('usage' => $usage->getName()));

            $buttons[$usage->getAbbreviation()] = array(
                'link' => $this->getCrud()->getNavigator()->getLinkFromRoute(
                        'seedUnit.changeUsage',
                        array(
                            'id'      => $seedUnit->getId(),
                            'usageId' => $usage->getId()
                        )
                    )
            );
        }

        return $buttons;
    }

    /**
     * @param UnitStatus $unitStatus
     *
     * @return array
     */
    private function initialiseShippingButtons(SeedUnit $entity, UnitStatus $unitStatus)
    {
        $allowedStatuses = UnitStatus::$ALLOWED_FROM[$unitStatus->getInternalName()];

        $buttons = array();
        foreach($allowedStatuses as $allowedStatus)
        {
            $buttons[$allowedStatus] = array(
                'link' => $this->getChangeStatusLink($entity, $allowedStatus)
            );
        }

        return $buttons;
    }

    /**
     * @param SeedUnit $entity
     * @param string   $status
     *
     * @return string
     */
    private function getChangeStatusLink($entity, $status)
    {

        $link = $this->getCrud()->getNavigator()->getLinkFromRoute(
            'seedUnit.changeShippingStatus',
            array(
                'id'     => $entity->getId(),
                'status' => $status
            )
        );

        return $link;
    }
}