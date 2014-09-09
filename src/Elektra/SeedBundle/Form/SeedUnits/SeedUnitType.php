<?php

namespace Elektra\SeedBundle\Form\SeedUnits;

use Elektra\CrudBundle\Form\Form;
use Elektra\CrudBundle\Repository\Repository;
use Elektra\SeedBundle\Entity\Events\UnitStatus;
use Elektra\SeedBundle\Entity\Events\UnitUsage;
use Elektra\SeedBundle\Entity\SeedUnits\SeedUnit;
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
            $locationOptions = $this->getFieldOptions('location')
                ->add('mapped', false)
                ->add('class', $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'WarehouseLocation')->getClassEntity())
                ->add('property', 'title');
            $commonGroup->add('location', 'entity', $locationOptions->toArray());
        }

        if ($options['crud_action'] != 'add')
        {
            $locationOptions = $this->getFieldOptions('location')
                ->add('read_only', true)
                ->add('class', $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'Location')->getClassEntity())
                ->add('property', 'title');
            $commonGroup->add('location', 'entity', $locationOptions->toArray());

            $statusOptions = $this->getFieldOptions('unitStatus')
                ->add('read_only', true)
                ->add('class', $this->getCrud()->getDefinition('Elektra', 'Seed', 'Events', 'UnitStatus')->getClassEntity())
                ->add('property', 'title');
            $commonGroup->add('unitStatus', 'entity', $statusOptions->toArray());

            $this->addHistoryGroup($builder, $options);
        }

        /* @var $entity SeedUnit */
        $entity = $options['data'];

        if ($entity->getUnitStatus()->getInternalName() == UnitStatus::DELIVERY_VERIFIED)
        {
            $usageOptions = $this->getFieldOptions('unitUsage')
                ->add('class', $this->getCrud()->getDefinition('Elektra', 'Seed', 'Events', 'UnitUsage')->getClassEntity())
                ->add('property', 'title');
            $commonGroup->add('unitUsage', 'entity', $usageOptions->toArray());
        }
    }

    private function addHistoryGroup(FormBuilderInterface $builder, array $options)
    {
        $historyGroup = $this->addFieldGroup($builder, $options, 'History'); // TRANSLATE this

        $eventsOptions = $this->getFieldOptions('events')
            ->add('relation_parent_entity', $options['data'])
            ->add('relation_child_type', $this->getCrud()->getDefinition('Elektra', 'Seed', 'Events', 'Event'))
            ->add('ordering_field', 'timestamp')
            ->add('ordering_direction', 'DESC')
            ->add('list_limit', 100);
        $historyGroup->add('events', 'relatedList', $eventsOptions->toArray());

        return $historyGroup;
    }


    /**
     * @inheritdoc
     */
    protected function initialiseButtons($crudAction, array $options)
    {
        /* @var $entity SeedUnit */
        $entity = $options['data'];
        /* @var $unitStatus UnitStatus */
        $unitStatus = $entity->getUnitStatus();

        if ($unitStatus->getInternalName() == UnitStatus::DELIVERY_VERIFIED)
        {
            $buttons = $this->initialiseUsageButtons($entity);
        }
        else
        {
            $buttons = $this->initialiseShippingButtons($entity, $unitStatus);
        }

        foreach ($buttons as $key => $button)
        {
            $this->addFormButton($key, 'link', $button, Form::BUTTON_TOP);
        }
    }

    /**
     * @param SeedUnit $seedUnit
     * @return array
     */
    private function initialiseUsageButtons(SeedUnit $seedUnit)
    {
        $buttons = array();
        /* @var $repo Repository */
        $repo = $this->getCrud()->getService('doctrine')
            ->getRepository($this->getCrud()->getNavigator()
                ->getDefinition('Elektra', 'Seed', 'Events', 'UnitUsage')->getClassRepository());

        $usages = $repo->findAll();

        $currentUsage = $seedUnit->getUnitUsage();

        /* @var $usage UnitUsage */
        foreach($usages as $usage)
        {
            if ($currentUsage != null && $usage->getId() == $currentUsage->getId())
                continue;

            // TODO language add
            //$this->getCrud()->getService("siteLanguage")->add('forms.seed_units.seed_unit.buttons.' . $usage->getAbbreviation(), $usage->getTitle());

            $buttons[$usage->getAbbreviation()] = array(
                'link' => $this->getCrud()->getNavigator()
                        ->getLinkFromRoute('seedUnit.changeUsage', array(
                            'id' => $seedUnit->getId(),
                            'usageId' => $usage->getId()
                        ))
            );
        }

        return $buttons;
    }

    /**
     * @param UnitStatus $unitStatus
     * @return array
     */
    private function initialiseShippingButtons(SeedUnit $entity, UnitStatus $unitStatus)
    {
        $buttons = array();
        switch($unitStatus->getInternalName())
        {
            case UnitStatus::RESERVED:
                $buttons[UnitStatus::SHIPPED] = array(
                    'link' => $this->getChangeStatusLink($entity, UnitStatus::SHIPPED)
                );
                break;

            case UnitStatus::SHIPPED:
                $buttons[UnitStatus::IN_TRANSIT] = array(
                    'link' => $this->getChangeStatusLink($entity, UnitStatus::IN_TRANSIT)
                );
                break;

            case UnitStatus::IN_TRANSIT:
                $buttons[UnitStatus::DELIVERED] = array(
                    'link' => $this->getChangeStatusLink($entity, UnitStatus::DELIVERED)
                );
                $buttons[UnitStatus::EXCEPTION] = array(
                    'link' => $this->getChangeStatusLink($entity, UnitStatus::EXCEPTION)
                );
                break;

            case UnitStatus::DELIVERED:
                $buttons[UnitStatus::DELIVERY_VERIFIED] = array(
                    'link' => $this->getChangeStatusLink($entity, UnitStatus::DELIVERY_VERIFIED)
                );
                $buttons[UnitStatus::ACKNOWLEDGE_ATTEMPT] = array(
                    'link' => $this->getChangeStatusLink($entity, UnitStatus::ACKNOWLEDGE_ATTEMPT)
                );
                break;

            case UnitStatus::ACKNOWLEDGE_ATTEMPT:
                $buttons[UnitStatus::DELIVERY_VERIFIED] = array(
                    'link' => $this->getChangeStatusLink($entity, UnitStatus::DELIVERY_VERIFIED)
                );
                $buttons[UnitStatus::AA1SENT] = array(
                    'link' => $this->getChangeStatusLink($entity, UnitStatus::AA1SENT)
                );
                break;

            case UnitStatus::AA1SENT:
                $buttons[UnitStatus::DELIVERY_VERIFIED] = array(
                    'link' => $this->getChangeStatusLink($entity, UnitStatus::DELIVERY_VERIFIED)
                );
                $buttons[UnitStatus::AA2SENT] = array(
                    'link' => $this->getChangeStatusLink($entity, UnitStatus::AA2SENT)
                );
                break;

            case UnitStatus::AA2SENT:
                $buttons[UnitStatus::DELIVERY_VERIFIED] = array(
                    'link' => $this->getChangeStatusLink($entity, UnitStatus::DELIVERY_VERIFIED)
                );
                $buttons[UnitStatus::AA3SENT] = array(
                    'link' => $this->getChangeStatusLink($entity, UnitStatus::AA3SENT)
                );
                break;

            case UnitStatus::AA3SENT:
                $buttons[UnitStatus::DELIVERY_VERIFIED] = array(
                    'link' => $this->getChangeStatusLink($entity, UnitStatus::DELIVERY_VERIFIED)
                );
                $buttons[UnitStatus::ESCALATION] = array(
                    'link' => $this->getChangeStatusLink($entity, UnitStatus::ESCALATION)
                );
                break;

            case UnitStatus::DELIVERY_VERIFIED:
                break;

            default:
            case UnitStatus::AVAILABLE:
            case UnitStatus::ESCALATION:
            case UnitStatus::EXCEPTION:
                break;
        }

        return $buttons;
    }

    /**
     * @param SeedUnit $entity
     * @param string $status
     * @return string
     */
    private function getChangeStatusLink($entity, $status)
    {
        $link = $this->getCrud()->getNavigator()->getLinkFromRoute('seedUnit.changeShippingStatus', array(
            'id' => $entity->getId(),
            'status' => $status
        ));

        return $link;
    }
}