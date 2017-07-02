<?php

namespace Stars\Client\Model;

use Stars\Core\Model\Model;

class TransactionModel extends Model
{

    public function __construct(array $ini = array()) 
    {
        if (!empty($ini)) {
            $this->id = $ini['id'];
            $this->client_id = $ini['client_id'];
            $this->store_id = $ini['store_id'];
            $this->employee_id = $ini['employee_id'];
        }
    }
    protected $tableName = 'transaction';

    protected $repositoryName = 'Stars\\Client\\Repository\\TransactionRepository';

    private $id;

    private $client_id;

    private $store_id;

    private $employee_id;

    private $rate_id;

    public function getId() {
        return $this->id;
    }

    public function getClientId()
    {
        return $this->client_id;
    }

    public function getStoreId()
    {
        return $this->store_id;
    }

    public function getEmployeeId()
    {
        return $this->store_id;
    }

    public function getRateId()
    {
        return $this->rate_id;
    }
}