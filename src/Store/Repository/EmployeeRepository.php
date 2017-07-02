<?php

namespace Stars\Store\Repository;

use Stars\Core\Repository\Repository;

class EmployeeRepository extends Repository
{
    public function fetchAll()
    {
        $statement = $this->connection->prepare("select s.*,st.name as store from employee s join store st on st.id = s.store_id");
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_CLASS, $this->modelName);
    }

    public function searchByName($name)
    {
        $statement = $this->connection->prepare("select s.*,st.name as store from employee s join store st on st.id = s.store_id where s.name like :name");
        $like = '%'.$name.'%';
        $statement->bindParam(':name', $like);
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_CLASS, $this->modelName);
    }

}