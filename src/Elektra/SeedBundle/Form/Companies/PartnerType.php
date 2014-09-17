<?php

namespace Elektra\SeedBundle\Form\Companies;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Elektra\CrudBundle\Form\Form as CrudForm;
use Symfony\Component\Form\FormBuilderInterface;

class PartnerType extends CrudForm
{

    /**
     * {@inheritdoc}
     */
    protected function getUniqueEntityFields()
    {

        return array(
            'shortName',
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function buildSpecificForm(FormBuilderInterface $builder, array $options)
    {

        $common = $this->addFieldGroup($builder, $options, 'common');

        $common->add('shortName', 'text', $this->getFieldOptions('shortName')->required()->notBlank()->toArray());
        $common->add('name', 'text', $this->getFieldOptions('name')->optional()->toArray());

        /*        $partnerTierFieldOptions = $this->getFieldOptions('partnerTier')->required()->notBlank();
                $partnerTierFieldOptions->add('class', $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'PartnerTier')->getClassEntity());
                $partnerTierFieldOptions->add('property', 'title');
                $common->add('partnerTier', 'entity', $partnerTierFieldOptions->toArray());*/

        $partnerTypeFieldOptions = $this->getFieldOptions('partnerType')->required()->notBlank();
        $partnerTypeFieldOptions->add('class', $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'PartnerType')->getClassEntity());
        $partnerTypeFieldOptions->add('property', 'title');
        $common->add('partnerType', 'entity', $partnerTypeFieldOptions->toArray());

        //        $common->add('unitsLimit', 'integer', $this->getFieldOptions('unitsLimit')->optional()->toArray());

        if ($options['crud_action'] == 'view') {
//            $locations2         = $this->addFieldGroup($builder, $options, 'locations2');
//            $locationDefinition = $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'CompanyLocation');
//            $this->getCrud()->setParent($options['data'], $this->getCrud()->getLinker()->getActiveRoute(), null);
//            $this->getCrud()->setDefinition($locationDefinition);
//            $locationOptions = $this->getFieldOptions('locations', false)->notMapped();
//            $locationOptions->add('class', $locationDefinition->getClassEntity());
//            $locationOptions->add('crud', $this->getCrud());
//            $locationOptions->add('child', $locationDefinition);
//            $locationOptions->add('parent', $this->getCrud()->getParentDefinition());
//            $locationOptions->add(
//                'query_builder',
//                function (EntityRepository $repository) use ($options) {
//
//                    $builder = $repository->createQueryBuilder('l');
//                    $builder->where('l.company = :company');
//                    $builder->setParameter('company', $options['data']);
//
//                    return $builder;
//                }
//            );
//            $locations2->add('locations2', 'entityTable', $locationOptions->toArray());

            $locations             = $this->addFieldGroup($builder, $options, 'locations');
            $locationsFieldOptions = $this->getFieldOptions('locations');
            $locationsFieldOptions->add('relation_parent_entity', $options['data']);
            $locationsFieldOptions->add('relation_child_type', $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'CompanyLocation'));
            $locationsFieldOptions->add('relation_name', 'company');
            //            $locationsFieldOptions->add('ordering_field', 'name');
            //            $locationsFieldOptions->add('ordering_direction', 'ASC');
            $locations->add('locations', 'relatedList', $locationsFieldOptions->toArray());
            // URGENT find a solution to display the persons at the company view



//            $persons2         = $this->addFieldGroup($builder, $options, 'persons2');
//            $personDefinition = $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'CompanyPerson');
//            $this->getCrud()->setParent($options['data'], $this->getCrud()->getLinker()->getActiveRoute(), null);
//            $this->getCrud()->setDefinition($personDefinition);
//            $personOptions = $this->getFieldOptions('persons', false)->notMapped();
//            $personOptions->add('class', $personDefinition->getClassEntity());
//            $personOptions->add('crud', $this->getCrud());
//            $personOptions->add('child', $personDefinition);
//            $personOptions->add('parent', $this->getCrud()->getParentDefinition());
//            $personOptions->add(
//                'query_builder',
//                function (EntityRepository $repository) use ($options) {
//
//                    $builder = $repository->createQueryBuilder('p');
//                    $builder->leftJoin('Elektra\SeedBundle\Entity\Companies\CompanyLocation', 'l');
//                    $builder->where('l.company = :company');
//                    $builder->setParameter('company', $options['data']);
//
////                    $builder = $repository->createQueryBuilder('p');
////                    $builder->leftJoin('Elektra\SeedBundle\Entity\Companies\CompanyLocation', 'l');
//////                    $builder->where('l.')
////
////                    $builder->where('l.company = ' . $options['data']->getId());
////echo $builder->getDQL().'<br />';
////                    echo $builder->getQuery()->getSQL().'<br />';
//                    return $builder;
//                }
//            );
//            $persons2->add('persons2', 'entityTable', $personOptions->toArray());

            $persons             = $this->addFieldGroup($builder, $options, 'persons');
            $personsFieldOptions = $this->getFieldOptions('persons', false);
            $personsFieldOptions->add('crud', $this->getCrud());
            $personsFieldOptions->add('relation_parent_entity', $options['data']);
            $personsFieldOptions->add('relation_child_type', $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'CompanyPerson'));
            $personsFieldOptions->add('relation_name', 'persons');
            $persons->add('persons', 'list', $personsFieldOptions->toArray());


            $customers             = $this->addFieldGroup($builder, $options, 'customers');
            $customersFieldOptions = $this->getFieldOptions('customers', false);
            $customersFieldOptions->add('crud', $this->getCrud());
            $customersFieldOptions->add('relation_parent_entity', $options['data']);
            $customersFieldOptions->add('relation_child_type', $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'Customer'));
            $customersFieldOptions->add('relation_name', 'customers');
            $customers->add('customers', 'list', $customersFieldOptions->toArray());

            //            $customers = $this->addFieldGroup($builder,$options,'customers');
//            $customersFieldOptions = $this->getFieldOptions('customers');
//            $customersFieldOptions->add('relation_parent_entity',$options['data']);
//            $customersFieldOptions->add('relation_child_type', $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'Customer'));
//            $customersFieldOptions->add('relation_name', 'partners');
//            $customers->add('customers','relatedList',$customersFieldOptions->toArray());

        }
    }
}