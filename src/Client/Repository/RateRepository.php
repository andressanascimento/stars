<?php

namespace Stars\Client\Repository;

use Stars\Core\Repository\Repository;

class RateRepository extends Repository
{
    /**
     * Return all clients
     * @return array of objects Stars\Client\Model\RateModel
     */
    public function fetchAll()
    {
        $statement = $this->connection->prepare("select r.*,c.name as client, s.name as store, e.name as employee from rate r join transaction t on t.id = r.transaction_id join store s on s.id = t.store_id join client c on c.id = t.client_id join employee e on e.id = t.employee_id");
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_CLASS, $this->modelName);
    }

    /**
     * Return all clients with a match name
     * @return array of objects Stars\Client\Model\RateModel
     */
    public function searchByName($name)
    {
        $statement = $this->connection->prepare("select r.*,c.name as client, s.name as store, e.name as employee from rate r join transaction t on t.id = r.transaction_id join store s on s.id = t.store_id join client c on c.id = t.client_id join employee e on e.id = t.employee_id where c.name like :name");
        $like = '%'.$name.'%';
        $statement->bindParam(':name', $like);
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_CLASS, $this->modelName);
    }

    /**
     * Check if a transaction already have a rate
     * @param int $transaction_id
     * @return boolean 
     */
    public function checkIfTransactionHasRate($transaction_id)
    {
        $statement = $this->connection->prepare("select * from rate r where r.transaction_id = :id");
        $statement->bindParam(':id', $transaction_id);
        $statement->execute();
        $list = $statement->fetchAll(\PDO::FETCH_CLASS, $this->modelName);

        if (!empty($list)) {
            return true;
        }

        return false;
    }
}