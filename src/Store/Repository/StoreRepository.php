<?php

namespace Stars\Store\Repository;

use Stars\Core\Repository\Repository;

class StoreRepository extends Repository
{
    /**
     * Return all stores
     * @return array of objects Stars\Store\Model\StoreModel
     */
    public function fetchAll()
    {
        $statement = $this->connection->prepare("select s.*,st.name as state from store s join state st on st.id = s.state_id");
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_CLASS, $this->modelName);
    }

    /**
     * Return all stores with a match name
     * @return array of objects Stars\Store\Model\StoreModel
     */
    public function searchByName($name)
    {
        $statement = $this->connection->prepare("select s.*,st.name as state from store s join state st on st.id = s.state_id where s.name like :name");
        $like = '%'.$name.'%';
        $statement->bindParam(':name', $like);
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_CLASS, $this->modelName);
    }

    /**
     * Delete a list of ids
     * @param array $list (id list to delete)
     * @return string|null string if failure (string message) or null for success
     * 
     */
    public function deleteList(array $list)
    {
        $message  = null;
        $ids = $this->checkIfClientsHasTransactions($list, $message);
            if (!empty($ids)) {
            $place = str_repeat ('?, ',  count ($ids) - 1) . '?';
            $statement = $this->connection->prepare("delete from store where id in ({$place})");

            $i = 1;
            foreach ($ids as &$value) {
                $statement->bindParam($i, $value);
                $i++;
            }
            
            $statement->execute();
        }
        return $message;
    }

    /**
     * Check dependency with transaction before delete
     * @param array $ids list of ids to check
     * @param string $message receive error messages
     * @return array list of stores without dependency
     * 
     */
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

    public function storeReport()
    {
        $sql = "select bs.id, bs.name,";
        $sql .= "(select count(*) from store s1 join transaction t1 on t1.store_id = s1.id join rate r1 on r1.transaction_id = t1.id where s1.id = bs.id and r1.rate = 1) rate_1, ";
        $sql .= "(select count(*) from store s2 join transaction t2 on t2.store_id = s2.id join rate r2 on r2.transaction_id = t2.id where s2.id = bs.id and r2.rate = 2) rate_2, ";
        $sql .="(select count(*) from store s3 join transaction t3 on t3.store_id = s3.id join rate r3 on r3.transaction_id = t3.id where s3.id = bs.id and r3.rate = 3) rate_3, ";
        $sql .="(select count(*) from store s4 join transaction t4 on t4.store_id = s4.id join rate r4 on r4.transaction_id = t4.id where s4.id = bs.id and r4.rate = 4) rate_4, ";
        $sql .="(select count(*) from store s5 join transaction t5 on t5.store_id = s5.id join rate r5 on r5.transaction_id = t5.id where s5.id = bs.id and r5.rate = 5) rate_5 ";
        $sql .= "from store bs";

        $statement = $this->connection->prepare($sql);
        $statement->execute();
        $list = $statement->fetchAll();
        //var_dump($list);

        foreach ($list as $row) {
            $report[$row['id']] = array(
            'name' => $row['name'],
            'data' => array(
                        array('name' => 'Nota 1', 'y' => (int) $row['rate_1']),
                        array('name' => 'Nota 2', 'y' => (int) $row['rate_2']),
                        array('name' => 'Nota 3', 'y' => (int) $row['rate_3']),
                        array('name' => 'Nota 4', 'y' => (int) $row['rate_4']),
                        array('name' => 'Nota 5', 'y' => (int) $row['rate_5']),
                    )
                
            );
        }
        return $report;
    }
}