<?php

namespace Stars\Client\Repository;

use Stars\Core\Repository\Repository;

class ClientRepository extends Repository
{
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
            $statement = $this->connection->prepare("delete from client where id in ({$place})");

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
     * @return array list of client without dependency
     * 
     */
    public function checkIfClientsHasTransactions(array $ids, &$message)
    {
        $place = str_repeat ('?, ',  count ($ids) - 1) . '?';
        $statement = $this->connection->prepare("select distinct c.id, c.name from client c join transaction t on t.client_id = c.id where c.id in ({$place})");
        
        $statement->execute($ids);
        
        $rows = $statement->fetchAll(\PDO::FETCH_CLASS, $this->modelName);
        if (!empty($rows)) {
            foreach ($rows as $row) {
                $id = array_search($row->getId(), $ids);
                unset($ids[$id]);
                $messages[] = $row->getName();
            }
            $message = implode(",",$messages)." tem pelo menos uma transação. Registro(s) não excluído(s)";
        }

        return $ids;
    }
}