<?php

namespace Stars\Store\Repository;

use Stars\Core\Repository\Repository;

class StoreRepository extends Repository
{
    public function fetchAll()
    {
        $statement = $this->connection->prepare("select s.*,st.name as state from store s join state st on st.id = s.state_id");
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_CLASS, $this->modelName);
    }

    public function searchByName($name)
    {
        $statement = $this->connection->prepare("select s.*,st.name as state from store s join state st on st.id = s.state_id where s.name like :name");
        $like = '%'.$name.'%';
        $statement->bindParam(':name', $like);
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_CLASS, $this->modelName);
    }

    public function deleteList(array $list)
    {
        $message  = null;
        $ids = $this->checkIfClientsHasTransactions($list, $message);

        $place = str_repeat ('?, ',  count ($ids) - 1) . '?';
        $statement = $this->connection->prepare("delete from store where id in ({$place})");

        $i = 1;
        foreach ($ids as &$value) {
            $statement->bindParam($i, $value);
            $i++;
        }
        
        $statement->execute();

        return $message;
    }

    public function checkIfClientsHasTransactions($ids, &$message)
    {
        $place = str_repeat ('?, ',  count ($ids) - 1) . '?';
        $statement = $this->connection->prepare("select distinct c.id, c.name from store c join transaction t on t.store_id = c.id where c.id in ({$place})");
        
        $statement->execute($ids);
        
        $rows = $statement->fetchAll(\PDO::FETCH_CLASS, $this->modelName);
        if (!empty($rows)) {
            foreach ($rows as $row) {
                $id = array_search($row->getId(), $ids);
                unset($ids[$id]);
                $messages[] = $row->getName();
            }
            $message = implode(" , ",$messages)." tem pelo menos uma transação. Registro(s) não excluído(s)";
        }

        return $ids;
    }
}