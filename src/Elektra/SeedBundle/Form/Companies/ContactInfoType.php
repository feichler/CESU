<?php

namespace Elektra\SeedBundle\Form\Companies;

use Elektra\CrudBundle\Form\Form as CrudForm;
use Symfony\Component\Form\FormBuilderInterface;

class ContactInfoType extends CrudForm
{

    /**
     * {@inheritdoc}
     */
    protected function getUniqueEntityFields()
    {

        return array(
            array('name', 'person', 'contactInfoType'),
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function buildSpecificForm(FormBuilderInterface $builder, array $options)
    {

        $common = $this->addFieldGroup($builder, $options, 'common');

        $parentDefinition = $this->getCrud()->getNavigator()->getDefinition('Elektra', 'Seed', 'Companies', 'Person');
        $this->addParentField('common', $builder, $options, $parentDefinition, 'person');

        $contactInfoTypeFieldOptions = $this->getFieldOptions('contactInfoType');
        $contactInfoTypeFieldOptions->required()->notBlank();
        $contactInfoTypeFieldOptions->add('class', $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'ContactInfoType')->getClassEntity());
        $contactInfoTypeFieldOptions->add('property', 'title');
        $common->add('contactInfoType', 'entity', $contactInfoTypeFieldOptions->toArray());
        $common->add('name', 'text', $this->getFieldOptions('name')->required()->notBlank()->toArray());
        $common->add('text', 'text', $this->getFieldOptions('text')->required()->notBlank()->toArray());
    }
}