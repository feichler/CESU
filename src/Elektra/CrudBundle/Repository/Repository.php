<?php

namespace Elektra\CrudBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

abstract class Repository extends EntityRepository
{

    /**
     * @var array
     */
    protected $aliasPool = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');

    /*************************************************************************
     * Default CRUD methods
     *************************************************************************/

    public function getEntries($page, $perPage, $search = null, $filters = null, $order = null)
    {

        $alias   = $this->getNextAlias();
        $builder = $this->prepareQueryBuilder($alias, $search, $filters, $order);
        $builder->setMaxResults($perPage);
        $builder->setFirstResult(($page - 1) * $perPage);

        $query = $builder->getQuery();

        $entries = $query->getResult();

        return $entries;
    }

    public function getCount($search = null, $filters = null, $order = null)
    {

        $alias   = $this->getNextAlias();
        $builder = $this->prepareQueryBuilder($alias, $search, $filters, $order);
        $builder->select($builder->expr()->count($alias));

        $count = $builder->getQuery()->getSingleScalarResult();

        return $count;
    }

    public function getCanDelete($entry)
    {

        // URGENT implement delete checks
        // NOTE needs to be implemented by specific repository classes

        return true;
    }

    /*************************************************************************
     * Helper methods
     *************************************************************************/

    /**
     * Get the next unused alias for the query builder
     *
     * @return string
     */
    protected function getNextAlias()
    {

        return array_shift($this->aliasPool);
    }

    /**
     * @param string $queryAlias
     * @param array  $search
     * @param array  $filters
     * @param array  $order
     *
     * @return QueryBuilder
     */
    protected function prepareQueryBuilder($queryAlias, $search, $filters, $order)
    {

        // Create a new query builder
        $builder = $this->createQueryBuilder($queryAlias);

        if ($search !== null) {
            // Add the search part(s)
            $this->prepareQueryBuilderSearch($builder, $queryAlias, $search);
        }

        if ($filters !== null) {
            // Add the filter part(s)
            $this->prepareQueryBuilderFilters($builder, $queryAlias, $filters);
        }

        if ($order !== null) {
            // Add the order part(s)
            $this->prepareQueryBuilderOrder($builder, $queryAlias, $order);
        }

        return $builder;
    }

    /**
     * @param QueryBuilder $builder
     * @param string       $queryAlias
     * @param array        $search
     */
    private function prepareQueryBuilderSearch(QueryBuilder $builder, $queryAlias, $search)
    {

        if ($search !== null) {
            $value  = $search['value'];
            $fields = $search['fields'];

            foreach ($fields as $field) {
                $composition = explode('.', $field);
                $field       = array_pop($composition);

                $lastAlias = $queryAlias;
                foreach ($composition as $join) {
                    $joinAlias = $this->getNextAlias();
                    $builder->leftJoin($lastAlias . '.' . $join, $joinAlias);
                    $lastAlias = $joinAlias;
                }

                $builder->orWhere($lastAlias . '.' . $field . ' LIKE :search');
            }

            $builder->setParameter(':search', '%' . $value . '%');
        }
    }

    /**
     * @param QueryBuilder $builder
     * @param string       $queryAlias
     * @param array        $filters
     */
    private function prepareQueryBuilderFilters(QueryBuilder $builder, $queryAlias, $filters)
    {

        if (!empty($filters)) {
            foreach ($filters as $field => $value) {
                switch ($value) {
                    case 'NULL':
                        $builder->andWhere($builder->expr()->isNull($queryAlias . '.' . $field));
                        break;
                    case 'NOT NULL':
                        $builder->andWhere($builder->expr()->isNotNull($queryAlias . '.' . $field));
                        break;
                    default:
                        $builder->andWhere($queryAlias . '.' . $field . '=' . $value);
                }
            }
        }
    }

    /**
     * @param QueryBuilder $builder
     * @param string       $queryAlias
     * @param array        $order
     */
    private function prepareQueryBuilderOrder(QueryBuilder $builder, $queryAlias, $order)
    {

        foreach ($order as $field => $direction) {
            $builder->addOrderBy($queryAlias . '.' . $field, $direction);
        }
    }
}