<?php

namespace Stars\Store\Model;

use Stars\Core\Model\Model;

class EmployeeModel extends Model
{

    public function __construct(array $ini = array()) 
    {
        if (!empty($ini)) {
            $this->id = $ini['id'];
            $this->name = $ini['name'];
            $this->store_id = $ini['store_id'];
        }
    }
    protected $tableName = 'employee';

    protected $repositoryName = 'Stars\\Store\\Repository\\EmployeeRepository';

    private $id;

    private $name;

    private $store_id;

    public function getId() {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getStoreId()
    {
        return $this->store_id;
    }
}