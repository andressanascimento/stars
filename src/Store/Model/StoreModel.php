<?php

namespace Stars\Store\Model;

use Stars\Core\Model\Model;

class StoreModel extends Model
{

    public function __construct(array $ini = array()) 
    {
        if (!empty($ini)) {
            $this->id = $ini['id'];
            $this->name = $ini['name'];
            $this->cnpj = $ini['cnpj'];
            $this->state_id = $ini['state_id'];
        }
    }
    protected $tableName = 'store';

    protected $repositoryName = 'Stars\\Store\\Repository\\StoreRepository';

    private $id;

    private $name;

    private $cnpj;

    private $state_id;

    public function getId() {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getCnpj()
    {
        return $this->cnpj;
    }

    public function getStateId()
    {
        return $this->state_id;
    }
}