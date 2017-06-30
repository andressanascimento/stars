<?php

namespace Stars\Client\Model;

class ClientModel
{
    private $tableName = 'client';

    private $repositoryName = 'Stars\\Client\\Repository\\ClientRepository';

    private $id;

    private $name;

    private $age;

    private $relationships = array();


    public function getId() {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getAge()
    {
        return $this->age;
    }

    public function getRelationships()
    {
        return $this->relationships;
    }

    public function getRepositoryName() 
    {
        return $this->repositoryName;
    }

    public function getTableName()
    {
        return $this->tableName;
    }
}