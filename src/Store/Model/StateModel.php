<?php

namespace Stars\Store\Model;

use Stars\Core\Model\Model;

class StateModel extends Model
{
    protected $tableName = 'state';

    protected $repositoryName = 'Stars\\Store\\Repository\\StateRepository';

    private $id;

    private $name;


    public function getId() {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }
}