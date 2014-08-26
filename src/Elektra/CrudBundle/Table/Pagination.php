<?php

namespace Elektra\CrudBundle\Table;

class Pagination
{

    /**
     * @var Table
     */
    protected $table;

    protected $actualPage;

    public function __construct(Table $table)
    {

        $this->table = $table;
    }

    public function setPage($page) {

        $this->actualPage=$page;
    }

    public function getPage() {

        return $this->actualPage;
    }
}