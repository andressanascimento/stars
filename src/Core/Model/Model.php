<?php

namespace Stars\Core\Model;

class Model
{

	public function getRepositoryName() 
    {
        return $this->repositoryName;
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    /**
     * Convert string with undercore to camelCase
     * @param string $string
     * @return string
     */
    private function dashesToCamelCase($string) 
    {

        $str = lcfirst(str_replace('-', '', ucwords($string, '-')));

        return $str;
    }
}