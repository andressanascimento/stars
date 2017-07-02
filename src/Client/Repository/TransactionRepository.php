<?php

namespace Stars\Client\Repository;

use Stars\Core\Repository\Repository;

class TransactionRepository extends Repository
{
    public function fetchAll()
    {
        $statement = $this->connection->prepare("select t.*,c.name as client, s.name as store, e.name as employee from transaction t join store s on s.id = t.store_id join client c on c.id = t.client_id join employee e on e.id = t.employee_id");
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_CLASS, $this->modelName);
    }

    public function searchByName($name)
    {
        $statement = $this->connection->prepare("select t.*,c.name as client, s.name as store, e.name as employee from transaction t join store s on s.id = t.store_id join client c on c.id = t.client_id join employee e on e.id = t.employee_id where c.name like :name");
        $like = '%'.$name.'%';
        $statement->bindParam(':name', $like);
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_CLASS, $this->modelName);
    }

}