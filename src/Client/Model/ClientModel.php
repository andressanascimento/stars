<?php

namespace Stars\Client\Model;

use Stars\Core\Model\Model;

class ClientModel extends Model
{
    protected $tableName = 'client';

    protected $repositoryName = 'Stars\\Client\\Repository\\ClientRepository';

    private $id;

    private $name;

    private $age;


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
}