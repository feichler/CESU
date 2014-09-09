<?php

namespace Elektra\SeedBundle\Form\Notes;

use Elektra\CrudBundle\Form\Form as CrudForm;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NoteType extends CrudForm
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

        $common = $this->addFieldGroup($builder, $options, 'common');

        $this->addParentField('common', $builder, $options, $this->getCrud()->getParentDefinition(), 'noteParent', false);
        $common->add('title', 'text', $this->getFieldOptions('title')->required()->notBlank()->toArray());
        $common->add('text', 'textarea', $this->getFieldOptions('text')->optional()->toArray());
    }
}