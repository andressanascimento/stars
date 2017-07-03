<?php

namespace Stars\Core\Service;

class DatabaseService
{

    private $connections;

    private $config;

    private $currentConnection;

    /**
     * @param array $config Content of config file database
     */
    protected function __construct(array $config)
    {
        $this->config = $config;
        if ($this->validateConfig($config, 'default')) {
            $this->connections['default'] = $this->createConnection($config,'default');
            $this->currentConnection = 'default';
        }
        
    }

    /**
     * Validate if a config database connection is in an valid format
     * @param array $config Content of config file database
     * @param string $connection_name Name of the connection
     * @return boolean
     */
    protected function validateConfig(array $config, $connection_name)
    {
        $config_options = $config[$connection_name];
        $validate_keys = array('dsn','username','password');
        foreach ($validate_keys as $key) {
            if (!array_key_exists($key, $config_options)) {
                throw new \Exception($key.' is required to connection '.$connection_name);
            }
        }
        
        return true;
    }

    /**
     * An config file could be filled with more than on connection
     * This method fetch one connection defined by $connection_name
     * @param array $config Config file
     * @param string $connection_name
     * @return \PDO
     */
    protected function createConnection(array $config, $connection_name)
    {
        return new \PDO($config[$connection_name]['dsn'], 
            $config[$connection_name]['username'], 
            $config[$connection_name]['password']
            );
    }

    public static function initialize($config)
    {
        return new static($config);
    }

    /**
     * Return a connection, create a new if connection not exists
     * @param $connection_name
     * @return DatabaseService
     */
    public function setConnection($connection_name = 'default')
    {

        if (!array_key_exists($connection_name, $this->connections) && !array_key_exists($connection_name, $this->config)) {
            throw new \Exception($conection_name.' does not have a configuration');
        }
        //if does not exists create a new connection
        if (!array_key_exists($connection_name, $this->connections)) {
            $this->connections[$connection_name] = $this->createConnection($this->config, $connection_name);
            $this->currentConnection = $connection_name;
        }
        return $this;
    }

    /**
     * Return the current connection
     * @return \PDO
     */
    public function getConnection()
    {
        return $this->connections[$this->currentConnection];
    }

    /**
     * Return a repository from a model
     * @param string $model_name
     * @return object
     */
    public function getRepository($model_name)
    {
        $model = new $model_name();
        $repository_name = $model->getRepositoryName();

        if (is_null($repository_name)) {
            throw new \Exception('repository name not defined');
        }

        $connection = $this->getConnection();
        $repository = new $repository_name($connection, $model_name, $model->getTableName());
        return $repository;
    }
}