<?php

namespace Elektra\SeedBundle\Form\Requests;

use Elektra\CrudBundle\Form\Form as CrudForm;
use Elektra\SeedBundle\Entity\Companies\Company;
use Elektra\SeedBundle\Entity\Companies\CompanyLocation;
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

    protected function buildSpecificForm(FormBuilderInterface $builder, array $options)
    {

        $self   = $this;
        $common = $this->addFieldGroup($builder, $options, 'common');

        if ($options['crud_action'] == 'view') {
            $common->add('requestNumber', 'text', $this->getFieldOptions('requestNumber')->toArray());
            $unitsGroup   = $this->addFieldGroup($builder, $options, 'units');
            $unitsOptions = $this->getFieldOptions('seedUnits', false);
            $unitsOptions->add('relation_parent_entity', $options['data']);
            $unitsOptions->add('relation_child_type', $this->getCrud()->getDefinition('Elektra', 'Seed', 'SeedUnits', 'SeedUnit'));
            $unitsGroup->add('seedUnits', 'relatedList', $unitsOptions->toArray());
        }

        $common->add('numberOfUnitsRequested', 'integer', $this->getFieldOptions('numberOfUnitsRequested')->notBlank()->required()->toArray());

        $companyOptions = $this->getFieldOptions('company')->required()->notBlank();
        $companyOptions->add('class', $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'Partner')->getClassEntity());
        $companyOptions->add('empty_value', 'Select Company');
        $companyOptions->add('property', 'shortName');
        $companyOptions->add('group_by', 'companyType');
        $common->add('company', 'entity', $companyOptions->toArray());

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($self) {

                $data = $event->getData();

                $self->companyModifier($event->getForm()->get('group_common'), $data->getCompany());
                $self->locationModifier($event->getForm()->get('group_common'), $data->getShippingLocation());
            }
        );

        $common->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event) use ($self) {

                $eventData = $event->getData();
                $em        = $self->getCrud()->getService('doctrine')->getManager();

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
        );
    }
}