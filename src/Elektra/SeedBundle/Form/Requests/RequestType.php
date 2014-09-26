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

    private function isDeliveryVerified(array $seedUnits)
    {
        /** @var $su SeedUnit */
        foreach($seedUnits as $su)
        {
            if ($su->getShippingStatus()->getInternalName() == UnitStatus::DELIVERY_VERIFIED)
            {
                return true;
            }
        }

        return false;
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
        $companyOptions->add('group_by', 'partnerType.name');
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
                /** @var $em ObjectManager */
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
                $allowedShippingStatuses = FormsHelper::getAllowedUnitStatuses($mgr, $seedUnits);
                $allowedUsages = $this->isDeliveryVerified($seedUnits) ? $mgr->getRepository('ElektraSeedBundle:Events\UnitUsage')->findAll() : array();
                $allowedSalesStatuses = $this->isDeliveryVerified($seedUnits) ? $mgr->getRepository('ElektraSeedBundle:Events\UnitSalesStatus')->findAll() : array();

                $buttons = FormsHelper::createModalButtonsOptions($allowedShippingStatuses, $allowedUsages, $allowedSalesStatuses);

                $unitsGroup->add($builder->create('modalButtons', 'modalButtons',
                    $this->getFieldOptions('modalButtons')
                        ->add('menus', $buttons)
                        ->toArray()));

                if (count($allowedShippingStatuses) > 0)
                {
                    $unitsGroup->add(
                        'changeStatus',
                        new ChangeUnitStatusType(),
                        array(
                            'mapped'                                => false,
                            ChangeUnitStatusType::OPT_DATA          => $seedUnits,
                            ChangeUnitStatusType::OPT_OBJECT_MANAGER => $mgr
                        )
                    );
                }

                if (count($allowedUsages) > 0)
                {
                    $unitsGroup->add(
                        'changeUsage',
                        new ChangeUnitUsageType(),
                        array(
                            'mapped'                                => false,
                            ChangeUnitUsageType::OPT_DATA          => $seedUnits,
                            ChangeUnitUsageType::OPT_OBJECT_MANAGER => $mgr
                        )
                    );
                }

                if (count($allowedSalesStatuses) > 0)
                {
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
    }
}