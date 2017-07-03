<?php

namespace Stars\Client\Repository;

use Stars\Core\Repository\Repository;

class TransactionRepository extends Repository
{
    /**
     * Return all clients
     * @return array of objects Stars\Client\Model\TransactionModel
     */
    public function fetchAll()
    {
        $statement = $this->connection->prepare("select t.*,c.name as client, s.name as store, e.name as employee, r.rate, r.id as rate_id from transaction t join store s on s.id = t.store_id join client c on c.id = t.client_id join employee e on e.id = t.employee_id left join rate r on r.transaction_id = t.id");
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_CLASS, $this->modelName);
    }

    /**
     * Return all transactions with a client match name
     * @return array of objects Stars\Client\Model\TransactionModel
     */
    public function searchByName($name)
    {
        $statement = $this->connection->prepare("select t.*,c.name as client, s.name as store, e.name as employee, r.rate, r.id as rate_id from transaction t join store s on s.id = t.store_id join client c on c.id = t.client_id join employee e on e.id = t.employee_id left join rate r on r.transaction_id = t.id where c.name like :name");
        $like = '%'.$name.'%';
        $statement->bindParam(':name', $like);
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_CLASS, $this->modelName);
    }

}