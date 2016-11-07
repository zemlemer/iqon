<?php

namespace App\Models;

use App\Contracts\QueryBuilderInterface;
use App\DB\QueryBuilder;
use App\Exceptions\WrongCallException;
use App\Exceptions\WrongParameterException;
use App\Structures\DbCondition;

abstract class BaseModel
{
    /**
     * @return string
     */
    abstract public function getTbName() : string;

    /**
     * @return string
     */
    abstract public function getPrimaryKey() : string;

    protected $data = [];
    protected $oldData = [];
    protected $attributes = [];

    public function __construct(array $data = [])
    {
        $this->setData($data);
    }

    public function toJSON()
    {
        return json_encode((object) $this->data);
    }

    public static function create(array $data = [])
    {
        $model = new static($data);
        $model->save();

        return $model;
    }

    public function save()
    {
        if(!$this->data) {
            throw new WrongCallException("Nothing to save");
        }

        $id = $this->getQueryBuilder()->replace($this->data)->execute();

        if ($id !== false) {
            $this->data['id'] = $id;
        }

        return $this;
    }

    protected function setData(array $data = [])
    {
        if($data) {
            $this->data = array_merge($this->data, array_intersect_key($data, array_flip($this->attributes)));
        }
    }

    protected function getQueryBuilder() : QueryBuilderInterface
    {
        $queryBuilder = new QueryBuilder($this->getTbName());

        return $queryBuilder->setModel($this);
    }

    public static function hydrate($data)
    {
        $model = new static((array) $data);

        return $model;
    }

    public static function hydrateAll(array $items) : array
    {
        return array_map(function($item)
        {
            return static::hydrate($item);
        }, $items);
    }

    public function findByID(int $id)
    {
        return  static::find([new DbCondition($this->getPrimaryKey(), '=', $id)])->firstOrFail();
    }

    public static function find(array $conditions) : QueryBuilderInterface
    {
        return (new static())->getQueryBuilder()
            ->select()
            ->setConditions($conditions);
    }

    public function __get($variable)
    {
        if(array_key_exists($variable, $this->data)) {
            return $this->data[$variable];
        }
        if(in_array($variable, $this->attributes)) {
            return null;
        }

        throw new WrongParameterException("Attribute ". $variable ." doesn't exist");
    }

    public function __set($variable, $value)
    {
        if(in_array($variable, $this->attributes)) {
            $this->data[$variable] = $value;
        } else {
            throw new WrongParameterException("Attribute ". $variable ." doesn't exist");
        }
    }

    public function __isset($variable)
    {
        if(!in_array($variable, $this->attributes)) {
            throw new WrongParameterException("Attribute ". $variable ." doesn't exist");
        }

        return isset($this->data[$variable]);
    }

    public final function __unset($name)
    {
        throw new WrongCallException("Forbidden action. Attributes list of ". get_called_class()
            . " is fixed: ". print_r($this->attributes, true));
    }
}
