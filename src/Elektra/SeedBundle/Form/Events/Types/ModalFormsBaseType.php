<?php

namespace Elektra\SeedBundle\Form\Events\Types;

use Doctrine\Common\Persistence\ObjectManager;
use Elektra\SeedBundle\Controller\EventFactory;
use Elektra\SeedBundle\Entity\Events\UnitSalesStatus;
use Elektra\SeedBundle\Entity\SeedUnits\SeedUnit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

abstract class ModalFormsBaseType extends AbstractType
{
    const OPT_DATA = 'data';
    const OPT_OBJECT_MANAGER = 'objectManager';
    const BUTTON_NAME = 'changeUsage';

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return "modalFormsBase";
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $data = $options[ModalFormsBaseType::OPT_DATA];

        if (is_array($data))
        {
            // TODO validate elements
        }
        else if ($data instanceof SeedUnit)
        {
            $data = array($data);
        }
        else
        {
            // TODO exception
        }

        /** @var ObjectManager $mgr */
        $mgr = $options[ModalFormsBaseType::OPT_OBJECT_MANAGER];

        /** @var EventFactory $eventFactory */
        $eventFactory = new EventFactory($mgr);

        $this->buildFields($builder, $data, $mgr, $eventFactory);
    }

    protected abstract function buildFields(FormBuilderInterface $builder, array $data, ObjectManager $mgr, EventFactory $eventFactory);

    /**
     * @inheritdoc
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setRequired(array(
            ModalFormsBaseType::OPT_DATA, ModalFormsBaseType::OPT_OBJECT_MANAGER
        ));
        $resolver->setDefaults(array(
            'label' => false
        ));
    }
}