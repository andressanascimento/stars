<?php

namespace Stars\Core\Service;

class DatabaseService
{

    private $connections;

    private $config;

    private $currentConnection;

    /**
     * @param array config
     */
    protected function __construct(array $config)
    {
        $this->config = $config;
        if ($this->validateConfig($config, 'default')) {
            $this->connections['default'] = $this->createConnection($config,'default');
            $this->currentConnection = 'default';
        }
        
    }

    protected function validateConfig($config, $connection_name)
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

    protected function createConnection($config, $connection_name)
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

    public function getConnection()
    {
        return $this->connections[$this->currentConnection];
    }

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