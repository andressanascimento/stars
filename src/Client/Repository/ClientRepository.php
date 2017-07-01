<?php

namespace Stars\Client\Repository;

use Stars\Core\Repository\Repository;

class ClientRepository extends Repository
{
	public function deleteList(array $list)
	{
		$table = $this->tableName;
		$where_list =  array();
		for ($i = 0; $i < count($list); $i++) {
			$where_sql[] = "?";
		}
		$where_sql = implode(",",$where_sql);

        $sql = "delete from {$table} where id in ({$where_sql})";
        $statement = $this->connection->prepare($sql);

        foreach($list as &$value) {
			$statement->bindParam($i, $value);
        }

        try {
            $statement->execute();
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return true;
	}
}