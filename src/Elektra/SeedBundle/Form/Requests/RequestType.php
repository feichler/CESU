<?php

namespace Elektra\SeedBundle\Form\Requests;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;
use Elektra\CrudBundle\Form\Form as CrudForm;
use Elektra\CrudBundle\Form\Form;
use Elektra\SeedBundle\Controller\EventFactory;
use Elektra\SeedBundle\Entity\Companies\Company;
use Elektra\SeedBundle\Entity\Companies\CompanyLocation;
use Elektra\SeedBundle\Entity\Events\UnitStatus;
use Elektra\SeedBundle\Entity\Requests\Request;
use Elektra\SeedBundle\Entity\SeedUnits\SeedUnit;
use Elektra\SeedBundle\Form\Events\Types\ChangeUnitSalesStatusType;
use Elektra\SeedBundle\Form\Events\Types\ChangeUnitStatusType;
use Elektra\SeedBundle\Form\Events\Types\ChangeUnitUsageType;
use Elektra\SeedBundle\Form\FormsHelper;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RequestType extends CrudForm
{

    /**
     * {@inheritdoc}
     */
    protected function setSpecificDefaultOptions(OptionsResolverInterface $resolver)
    {
    }

    public function locationModifier(FormInterface $form, CompanyLocation $location = null)
    {

        $persons = array();
        if ($location != null) {
            $persons = $location->getPersons();
        }

        $receiverOptions = $this->getFieldOptions('receiverPerson')->required()->notBlank();
        $receiverOptions->add('class', $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'CompanyPerson')->getClassEntity());
        $receiverOptions->add('property', 'title');
        $receiverOptions->add('empty_value', 'Select Receiver');
        $receiverOptions->add('choices', $persons);
        $form->add('receiverPerson', 'entity', $receiverOptions->toArray());
    }

    public function companyModifier(FormInterface $form, Company $company = null)
    {

        $persons   = array();
        $locations = array();
        if ($company != null) {
            $persons   = $company->getPersons();
            $locations = $company->getLocations();
        }

        $requesterOptions = $this->getFieldOptions('requesterPerson')->required()->notBlank();
        $requesterOptions->add('class', $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'CompanyPerson')->getClassEntity());
        $requesterOptions->add('property', 'title');
        $requesterOptions->add('empty_value', 'Select Requester');
        $requesterOptions->add('choices', $persons);
        $form->add('requesterPerson', 'entity', $requesterOptions->toArray());

        $shippingOptions = $this->getFieldOptions('shippingLocation')->required()->notBlank();
        $shippingOptions->add('class', $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'CompanyLocation')->getClassEntity());
        $shippingOptions->add('property', 'title');
        $shippingOptions->add('empty_value', 'Select Shipping Location');
        $shippingOptions->add('choices', $locations);
        $form->add('shippingLocation', 'entity', $shippingOptions->toArray());
    }

    private function createModalButtonsOptions(FormBuilderInterface $builder, array $allowedStatuses, array $allowedUsages, array $allowedSalesStatuses)
    {
        $shippingButtons = array();
        foreach ($allowedStatuses as $status)
        {
            $shippingButtons[ChangeUnitStatusType::getModalId($status)] = $status->getTitle();
        }

        $usageButtons = array();
        foreach ($allowedUsages as $usage)
        {
            $usageButtons[ChangeUnitUsageType::getModalId($usage)] = $usage->getTitle();
        }

        $salesButtons = array();
        foreach ($allowedSalesStatuses as $status)
        {
            $salesButtons[ChangeUnitSalesStatusType::getModalId($status)] = $status->getTitle();
        }

        $buttons = array(
            'Shipping' => $shippingButtons,
            'Usage' => $usageButtons,
            'Sales' => $salesButtons
        );

        foreach ($buttons as $key => $value)
        {
            if (count($value) == 0)
            {
                unset($buttons[$key]);
            }
        }

        $modalButtonsOptions = $this->getFieldOptions('modalButtons');
        $modalButtonsOptions->add('menus', $buttons);

        $modalButtons = $builder->create('modalButtons', 'modalButtons', $modalButtonsOptions->toArray());

        return $modalButtons;
    }

    private function isDeliveryVerified(array $seedUnits)
    {
        foreach($seedUnits as $su)
        {
            if ($su->getShippingStatus()->getInternalName() == UnitStatus::DELIVERY_VERIFIED)
            {
                return true;
            }
        }

        return false;
    }

    private function getAllowedUnitStatuses(ObjectManager $mgr, $seedUnits)
    {

        $names = FormsHelper::getAllowedStatuses($seedUnits);

        $statuses = array();
        if (count($names) > 0) {
            $repo = $mgr->getRepository('ElektraSeedBundle:Events\UnitStatus');
            $qb   = $repo->createQueryBuilder('us');
            $qb->where($qb->expr()->in('us.internalName', $names));
            $statuses = $qb->getQuery()->getResult();
        }

        return $statuses;
    }

    protected function buildSpecificForm(FormBuilderInterface $builder, array $options)
    {

        $self        = $this;
        $commonGroup = $this->addFieldGroup($builder, $options, 'common');

        if ($options['crud_action'] == 'view') {
            // request number
            $commonGroup->add('requestNumber', 'text', $this->getFieldOptions('requestNumber')->toArray());
        }

        // # of units requested
        $commonGroup->add('numberOfUnitsRequested', 'integer', $this->getFieldOptions('numberOfUnitsRequested')->notBlank()->required()->toArray());

        // requesting company
        $companyOptions = $this->getFieldOptions('company')->required()->notBlank();
        $companyOptions->add('class', $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'Partner')->getClassEntity());
        $companyOptions->add('empty_value', 'Select Company');
        $companyOptions->add('property', 'shortName');
        $companyOptions->add('group_by', 'companyType');
        $commonGroup->add('company', 'entity', $companyOptions->toArray());

        // location & requesting person
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($self) {

                $data = $event->getData();

                $self->companyModifier($event->getForm()->get('group_common'), $data->getCompany());
                $self->locationModifier($event->getForm()->get('group_common'), $data->getShippingLocation());
            }
        );

        $commonGroup->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event) use ($self) {

                $eventData = $event->getData();
                $em        = $self->getCrud()->getService('doctrine')->getManager();

                if($eventData) {
                if (array_key_exists('company', $eventData)) {
                    $companyId = $eventData['company'];
                    $company   = $em->find($self->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'Company')->getClassEntity(), $companyId);
                    $self->companyModifier($event->getForm(), $company);
                }
                if (array_key_exists('shippingLocation', $eventData)) {
                    $shippingId = $eventData['shippingLocation'];
                    $shipping   = $em->find($self->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'CompanyLocation')->getClassEntity(), $shippingId);
                    $self->locationModifier($event->getForm(), $shipping);
                }
                }
            }
        );

        if ($options['crud_action'] == 'view') {
            // seed units group
            $unitsGroup = $this->addFieldGroup($builder, $options, 'units');
            /** @var ObjectManager $mgr */
            $mgr       = $this->getCrud()->getService('doctrine')->getManager();
            /** @var array $seedUnits */
            $seedUnits = $options['data']->getSeedUnits()->toArray();

            if (count($seedUnits) > 0) {
                $allowedShippingStatuses = $this->getAllowedUnitStatuses($mgr, $seedUnits);
                $allowedUsages = $this->isDeliveryVerified($seedUnits) ? $mgr->getRepository('ElektraSeedBundle:Events\UnitUsage')->findAll() : array();
                $allowedSalesStatuses = $this->isDeliveryVerified($seedUnits) ? $mgr->getRepository('ElektraSeedBundle:Events\UnitSalesStatus')->findAll() : array();
                $unitsGroup->add($this->createModalButtonsOptions($builder, $allowedShippingStatuses, $allowedUsages, $allowedSalesStatuses));

                $unitsGroup->add(
                    'changeStatus',
                    new ChangeUnitStatusType(),
                    array(
                        'mapped'                                => false,
                        ChangeUnitStatusType::OPT_DATA          => $seedUnits,
                        ChangeUnitStatusType::OPT_OBJECT_MANAGER => $mgr
                    )
                );

                $unitsGroup->add(
                    'changeUsage',
                    new ChangeUnitUsageType(),
                    array(
                        'mapped'                                => false,
                        ChangeUnitUsageType::OPT_DATA          => $seedUnits,
                        ChangeUnitUsageType::OPT_OBJECT_MANAGER => $mgr
                    )
                );

                $unitsGroup->add(
                    'changeUnitSalesStatus',
                    new ChangeUnitSalesStatusType(),
                    array(
                        'mapped'                                => false,
                        ChangeUnitSalesStatusType::OPT_DATA          => $seedUnits,
                        ChangeUnitSalesStatusType::OPT_OBJECT_MANAGER => $mgr
                    )
                );

            }

            // seed units
            $lastLangKey = $this->getCrud()->getLanguageKey();
            $this->getCrud()->setOverridenLangKey($lastLangKey);
            $unitsDefinition = $this->getCrud()->getDefinition('Elektra', 'Seed', 'SeedUnits', 'SeedUnit');

            $this->getCrud()->setDefinition($unitsDefinition);
            $this->getCrud()->setParent($options['data'], $this->getCrud()->getLinker()->getActiveRoute(), null);

            $unitsOptions = $this->getFieldOptions('seedUnits', false);
            $unitsOptions->add('multiple', true);
            $unitsOptions->add('class', $unitsDefinition->getClassEntity());
            $unitsOptions->add('crud', $this->getCrud());
            $unitsOptions->add('child', $unitsDefinition);
            $unitsOptions->add('parent', $this->getCrud()->getParentDefinition());
            $unitsOptions->add(
                'query_builder',
                function (EntityRepository $repository) use ($options) {

                    $builder = $repository->createQueryBuilder('su');
                    $builder->where('su.request = :request');
                    $builder->setParameter('request', $options['data']);

                    return $builder;
                }
            );
            $unitsGroup->add('seedUnits', 'entityTable', $unitsOptions->toArray());
            $this->getCrud()->resetOverridenLangKey();
        }


//        $self   = $this;
//        $common = $this->addFieldGroup($builder, $options, 'common');
//
//        if ($options['crud_action'] == 'view') {
//            // request number
//            $common->add('requestNumber', 'text', $this->getFieldOptions('requestNumber')->toArray());
//
//            $unitsGroup = $this->addFieldGroup($builder, $options, 'units');
//            /** @var ObjectManager $mgr */
//            $mgr       = $this->getCrud()->getService('doctrine')->getManager();
//            $seedUnits = $options['data']->getSeedUnits()->toArray();
//
//            if (count($seedUnits) > 0) {
//                $allowedStatuses = $this->getAllowedUnitStatuses($mgr, $seedUnits);
//                $unitsGroup->add($this->createModalButtonsOptions($builder, $allowedStatuses));
//
//                $unitsGroup->add(
//                    'changeStatus',
//                    new ChangeUnitStatusType(),
//                    array(
//                        'mapped'                                => false,
//                        ChangeUnitStatusType::OPT_DATA          => $seedUnits,
//                        ChangeUnitStatusType::OPT_EVENT_FACTORY => new EventFactory($mgr)
//                    )
//                );
//            }
//
//            // seed units
//            $lastLangKey = $this->getCrud()->getLanguageKey();
//            $this->getCrud()->setOverridenLangKey($lastLangKey);
//            $unitsDefinition = $this->getCrud()->getDefinition('Elektra', 'Seed', 'SeedUnits', 'SeedUnit');
//
//            $this->getCrud()->setDefinition($unitsDefinition);
//            $this->getCrud()->setParent($options['data'], $this->getCrud()->getLinker()->getActiveRoute(), null);
//
//            $unitsOptions = $this->getFieldOptions('seedUnits', false);
//            $unitsOptions->add('multiple', true);
//            $unitsOptions->add('class', $unitsDefinition->getClassEntity());
//            $unitsOptions->add('crud', $this->getCrud());
//            $unitsOptions->add('child', $unitsDefinition);
//            $unitsOptions->add('parent', $this->getCrud()->getParentDefinition());
//            $unitsOptions->add(
//                'query_builder',
//                function (EntityRepository $repository) use ($options) {
//
//                    $builder = $repository->createQueryBuilder('su');
//                    $builder->where('su.request = :request');
//                    $builder->setParameter('request', $options['data']);
//
//                    return $builder;
//                }
//            );
//            $unitsGroup->add('seedUnits', 'entityTable', $unitsOptions->toArray());
//
//            //            $unitsGroup   = $this->addFieldGroup($builder, $options, 'units');
//            //            $unitsOptions = $this->getFieldOptions('seedUnits', false);
//            //            $unitsOptions->add('relation_parent_entity', $options['data']);
//            //            $unitsOptions->add('relation_child_type', $this->getCrud()->getDefinition('Elektra', 'Seed', 'SeedUnits', 'SeedUnit'));
//            //            $unitsOptions->add('checkboxes', true);
//            //            $unitsGroup->add('seedUnits', 'relatedList', $unitsOptions->toArray());
//            //
//            //            $uDefinition = $this->getCrud()->getDefinition('Elektra', 'Seed', 'SeedUnits', 'SeedUnit');
//            //            $uGroup      = $this->addFieldGroup($builder, $options, 'units2');
//            //            $uOptions    = $this->getFieldOptions('seedUnits', false);
//            //            $this->getCrud()->setParent($options['data'], $this->getCrud()->getLinker()->getActiveRoute(), null);
//            //            $this->getCrud()->setDefinition($uDefinition);
//            //            //            $uOptions = $this->getFieldOptions('locations', false)->notMapped();
//            //            $uOptions->add('multiple', true);
//            //            $uOptions->add('class', $uDefinition->getClassEntity());
//            //            $uOptions->add('crud', $this->getCrud());
//            //            $uOptions->add('child', $uDefinition);
//            //            $uOptions->add('parent', $this->getCrud()->getParentDefinition());
//            //            $uOptions->add(
//            //                'query_builder',
//            //                function (EntityRepository $repository) use ($options) {
//            //
//            //                    $builder = $repository->createQueryBuilder('u');
//            //                    $builder->where('u.request = :request');
//            //                    $builder->setParameter('request', $options['data']);
//            //
//            //                    return $builder;
//            //                }
//            //            );
//            //            $uGroup->add('seedUnits', 'entityTable', $uOptions->toArray());
//        }
//
//        $common->add('numberOfUnitsRequested', 'integer', $this->getFieldOptions('numberOfUnitsRequested')->notBlank()->required()->toArray());
//
//        $companyOptions = $this->getFieldOptions('company')->required()->notBlank();
//        $companyOptions->add('class', $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'Partner')->getClassEntity());
//        $companyOptions->add('empty_value', 'Select Company');
//        $companyOptions->add('property', 'shortName');
//        $companyOptions->add('group_by', 'companyType');
//        $common->add('company', 'entity', $companyOptions->toArray());
//
//        //        if (in_array($options['crud_action'], array('add', 'edit'))) {
//        $builder->addEventListener(
//            FormEvents::PRE_SET_DATA,
//            function (FormEvent $event) use ($self) {
//
//                $data = $event->getData();
//
//                $self->companyModifier($event->getForm()->get('group_common'), $data->getCompany());
//                $self->locationModifier($event->getForm()->get('group_common'), $data->getShippingLocation());
//            }
//        );
//
//        $common->addEventListener(
//            FormEvents::PRE_SUBMIT,
//            function (FormEvent $event) use ($self) {
//
//                $eventData = $event->getData();
//                $em        = $self->getCrud()->getService('doctrine')->getManager();
//
//                if (array_key_exists('company', $eventData)) {
//                    $companyId = $eventData['company'];
//                    $company   = $em->find($self->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'Company')->getClassEntity(), $companyId);
//                    $self->companyModifier($event->getForm(), $company);
//                }
//                if (array_key_exists('shippingLocation', $eventData)) {
//                    $shippingId = $eventData['shippingLocation'];
//                    $shipping   = $em->find($self->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'CompanyLocation')->getClassEntity(), $shippingId);
//                    $self->locationModifier($event->getForm(), $shipping);
//                }
//            }
//        );
//        //        }
//
//        $this->getCrud()->resetOverridenLangKey();
    }

    //    protected function initialiseButtons($crudAction, array $options)
    //    {
    //
    //        $seedUnits = $options['data']->getSeedUnits();
    //
    //        // calculate allowed target statuses
    //        $statuses = array();
    //        foreach ($seedUnits as $seedUnit) {
    //            $allowed  = UnitStatus::$ALLOWED_FROM[$seedUnit->getUnitStatus()->getInternalName()];
    //            $statuses = array_merge($statuses, $allowed);
    //        }
    //
    //        $statuses = array_unique($statuses);
    //
    //        foreach ($statuses as $status) {
    //            $this->addFormButton($status, 'submit', array(), Form::BUTTON_TOP);
    //        }
    //    }
}