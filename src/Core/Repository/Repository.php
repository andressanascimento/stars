<?php

namespace Stars\Core\Repository;



class Repository
{
    protected $connection;

    protected $tableName;

    protected $modelName;

    public function __construct(\PDO $connection, $modelName, $tableName)
    {
        $this->connection = $connection;
        $this->modelName = $modelName;
        $this->tableName = $tableName;
    }

    public function fetchAll()
    {
        $table = $this->tableName;
        $statement = $this->connection->prepare("select * from {$table}");
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_CLASS, $this->modelName);
    }

    public function findBy(array $parameters)
    {
        $table = $this->tableName;
        //where clause
        $where_sql = $parameters;
        array_walk($where_sql, function (&$v, $k) { $v = $k." = :".$k; });
        $where_clause = implode(" and ", $where_sql);

        $sql = "select * from {$table} where {$where_clause}";

        $statement = $this->connection->prepare($sql);
        foreach ($parameters as $param => &$value) {
            $statement->bindParam($param, $value);
        }

        $statement->execute();
        
        return $statement->fetchAll(\PDO::FETCH_CLASS, $this->modelName);
    }

    public function findByOne(array $parameters)
    {
        $table = $this->tableName;
        //where clause
        $where_sql = $parameters;
        array_walk($where_sql, function (&$v, $k) { $v = $k." = :".$k; });
        $where_clause = implode(" and ", $where_sql);

        $sql = "select * from {$table} where {$where_clause}";

        $statement = $this->connection->prepare($sql);
        foreach ($parameters as $param => &$value) {
            $statement->bindParam($param, $value);
        }
        $statement->setFetchMode(\PDO::FETCH_CLASS, $this->modelName); 
        $statement->execute();
        
        return $statement->fetch();
    }

    public function searchByName($name)
    {
        $table = $this->getTableName();
        $statement = $this->connection->prepare("select * from {$table} where name like :name");
        $like = '%'.$name.'%';
        $statement->bindParam(':name', $like);
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_CLASS, $this->modelName);
    }

    public function insert(array $parameters)
    {
        $table = $this->tableName;
        $columns = implode(",",array_keys($parameters));
        $values_keys = ":".implode(",:",array_keys($parameters));

        $sql = "insert into {$table} ({$columns}) values ({$values_keys})";

        $statement = $this->connection->prepare($sql);
        foreach ($parameters as $param => &$value) {
            $statement->bindParam($param, $value);
        }
        try {
            $statement->execute();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        
        return true;
    }

    public function update(array $parameters, array $where = null)
    {
        $table = $this->tableName;
        $set_sql = $parameters;
        $where_sql = $where;

        array_walk($set_sql, function (&$v, $k) { $v = $k."= :".$k; });
        array_walk($where_sql, function (&$v, $k) { $v = $k." = :".$k; });
        $set_clause = implode(",",$set_sql);
        $where_clause = implode(" and ",$where_sql);

        $sql = "update {$table} set {$set_clause}";

        $bind_params = $parameters;
        if (!is_null($where)) {
            $sql .= " where ".$where_clause;
            $bind_params += $where;
        }

        $statement = $this->connection->prepare($sql);
        foreach ($bind_params as $param => &$value) {
            $statement->bindParam($param, $value);
        }
        try {
            $statement->execute();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        
        return true;
    }

    public function delete(array $where = null)
    {
        $table = $this->tableName;

        $sql = "delete from {$table}";

        if (!is_null($where)) {
            //where clause
            $where_sql = $where;
            array_walk($where_sql, function (&$v, $k) { $v = $k." = :".$k; });
            $where_clause = implode(" and ",$where_sql);

            $sql .= " where ".$where_clause;
        }

        $statement = $this->connection->prepare($sql);

        if (!is_null($where)) {
            foreach ($where as $param => &$value) {
                $statement->bindParam($param, $value);
            }
        }
        
        try {
            $statement->execute();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        
        return true;
    }

    public function deleteList(array $list)
    {
        $table = $this->tableName;
        $where_list =  array();
        for ($i = 0; $i < count($list); $i++) {
            $where_sql[] = "?";
        }
        $where_sql = implode(" , ",$where_sql);

        $sql = "delete from {$table} where id in ({$where_sql})";
        $statement = $this->connection->prepare($sql);

        $i = 1;
        foreach($list as &$value) {
            $statement->bindParam($i, $value);
            $i++;
        }

        try {
            $statement->execute();
        } catch (\Exception $e) {

            var_dump($e->getMessage());
            return $e->getMessage();
        }

        return true;
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }
}