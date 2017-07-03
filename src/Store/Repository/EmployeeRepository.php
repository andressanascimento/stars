<?php

namespace Stars\Store\Repository;

use Stars\Core\Repository\Repository;

class EmployeeRepository extends Repository
{
    /**
     * Return all employees
     * @return array of objects Stars\Store\Model\EmployeeModel
     */
    public function fetchAll()
    {
        $statement = $this->connection->prepare("select s.*,st.name as store from employee s left join store st on st.id = s.store_id");
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_CLASS, $this->modelName);
    }

    /**
     * Return all employee with a match name
     * @return array of objects Stars\Store\Model\EmployeeModel
     */
    public function searchByName($name)
    {
        $statement = $this->connection->prepare("select s.*,st.name as store from employee s left join store st on st.id = s.store_id where s.name like :name");
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
            $statement = $this->connection->prepare("delete from employee where id in ({$place})");

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
     * @return array list of employees without dependency
     * 
     */
    public function checkIfClientsHasTransactions($ids, &$message)
    {
        $place = str_repeat ('?, ',  count ($ids) - 1) . '?';
        $statement = $this->connection->prepare("select distinct c.id, c.name from employee c join transaction t on t.employee_id = c.id where c.id in ({$place})");
        
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