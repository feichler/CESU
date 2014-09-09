<?php

namespace Elektra\SeedBundle\Form\Companies;

use Elektra\CrudBundle\Form\Form as CrudForm;
use Elektra\SiteBundle\Form\DataTransformer\ToUppercaseTransformer;
use Symfony\Component\Form\FormBuilderInterface;

class RegionType extends CrudForm
{

    /**
     * {@inheritdoc}
     */
    protected function getUniqueEntityFields()
    {

        return array(
            'name',
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function buildSpecificForm(FormBuilderInterface $builder, array $options)
    {

        $common = $this->addFieldGroup($builder, $options, 'common');

        $region = $common->create('name', 'text', $this->getFieldOptions('name')->required()->notBlank()->toArray());
        $common->add($region->addModelTransformer(new ToUppercaseTransformer()));

        if ($options['crud_action'] == 'view') {
            $countries             = $this->addFieldGroup($builder, $options, 'countries');
            $countriesFieldOptions = $this->getFieldOptions('persons');
            $countriesFieldOptions->add('relation_parent_entity', $options['data']);
            $countriesFieldOptions->add('relation_child_type', $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'Country'));
            $countries->add('countries', 'relatedList', $countriesFieldOptions->toArray());
        }
    }
}