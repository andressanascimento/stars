<?php

namespace Stars\Client\Model;

use Stars\Core\Model\Model;

class RateModel extends Model
{

    public function __construct(array $ini = array()) 
    {
        if (!empty($ini)) {
            $this->id = $ini['id'];
            $this->rate = $ini['rate'];
            $this->transaction_id = $ini['transaction_id'];
        }
    }
    protected $tableName = 'rate';

    protected $repositoryName = 'Stars\\Client\\Repository\\RateRepository';

    private $id;

    private $rate;

    private $transaction_id;

    private $client;

    private $store;

    private $employee;


    public function getId() {
        return $this->id;
    }

    public function getTransactionId()
    {
        return $this->transaction_id;
    }

    public function getRate()
    {
        return $this->rate;
    }

    public function getClient()
    {
        return $this->client;
    }

    public function getStore()
    {
        return $this->store;
    }

    public function getEmployee()
    {
        return $this->store;
    }    
}