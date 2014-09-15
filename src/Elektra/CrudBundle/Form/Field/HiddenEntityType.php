<?php

namespace Elektra\CrudBundle\Form\Field;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;
use Elektra\CrudBundle\Form\DataTransformer\EntityToIdTransformer;
use Symfony\Bridge\Doctrine\Form\DoctrineOrmTypeGuesser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class HiddenEntityType extends AbstractType
{
    /**
     * @var \Doctrine\Common\Persistence\ManagerRegistry
     */
    private $registry;

    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @var \Symfony\Bridge\Doctrine\Form\DoctrineOrmTypeGuesser
     */
    private $guesser;

    /**
     * @param ManagerRegistry $registry
     * @param DoctrineOrmTypeGuesser $guesser
     */
    public function __construct(ManagerRegistry $registry, DoctrineOrmTypeGuesser $guesser)
    {
        $this->registry = $registry;
        $this->om = $registry->getManager();
        $this->guesser = $guesser;
    }

    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new EntityToIdTransformer($this->om);
        $builder->addModelTransformer($transformer);

        if($options['class'] === null) {

            $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) use ($transformer, $builder)
            {
                /* @var $form \Symfony\Component\Form\Form */
                $form = $event->getForm();
                $class = $form->getParent()->getConfig()->getDataClass();
                $property = $form->getName();
                $guessedType = $this->guesser->guessType($class, $property);
                $options = $guessedType->getOptions();

                $transformer->setEntityClass($options['class']);

            });
        }
        else
        {
            $transformer->setEntityClass($options['class']);
        }
    }
    public function getParent()
    {
        return 'hidden';
    }

    public function getName()
    {
        return 'hiddenEntity';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver); // TODO: Change the autogenerated stub

        $resolver->setDefaults(array(
            'class' => null
        ));
    }


}