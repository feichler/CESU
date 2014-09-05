<?php

namespace Elektra\SeedBundle\Form\SeedUnits;

use Elektra\CrudBundle\Form\Form;
use Elektra\SeedBundle\Entity\Events\UnitStatus;
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

        $modelOptions = $this->getFieldOptions('serialNumber')
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
    }

    private function addHistoryGroup(FormBuilderInterface $builder, array $options)
    {
        $historyGroup = $this->addFieldGroup($builder, $options, 'History'); // TRANSLATE this

        $eventsOptions = $this->getFieldOptions('events')
            ->add('relation_parent_entity', $options['data'])
            ->add('relation_child_type', $this->getCrud()->getDefinition('Elektra', 'Seed', 'Events', 'Event'));
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

        $buttons = array();
        switch($unitStatus->getInternalName())
        {
            case UnitStatus::RESERVED:
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

        foreach ($buttons as $key => $button)
        {
            $this->addFormButton($key, 'link', $button, Form::BUTTON_TOP);
        }
    }

    /**
     * @param SeedUnit $entity
     * @param string $status
     * @return string
     */
    private function getChangeStatusLink($entity, $status)
    {
        $link = $this->getCrud()->getNavigator()->getLinkFromRoute('seedUnit.changeStatus', array(
            'id' => $entity->getId(),
            'status' => $status
        ));

        return $link;
    }
}