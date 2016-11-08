<?php

namespace App\Models;

use App\Contracts\QueryBuilderInterface;
use App\DB\QueryBuilder;
use App\Exceptions\WrongCallException;
use App\Exceptions\WrongParameterException;
use App\Structures\DbCondition;

/**
 * Class BaseModel
 *
 * @package App\Models
 */
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

    /**
     * @var array
     */
    protected $data = [];
    /**
     * @var array
     */
    protected $oldData = [];
    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * BaseModel constructor.
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->setData($data);
    }

    /**
     * @return string
     */
    public function toJSON()
    {
        return json_encode((object) $this->data);
    }

    /**
     * @param array $data
     * @return static
     */
    public static function create(array $data = [])
    {
        $model = new static($data);
        $model->save();

        return $model;
    }

    /**
     * @return $this
     */
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

    /**
     * @param array $data
     */
    protected function setData(array $data = [])
    {
        if($data) {
            $this->data = array_merge($this->data, array_intersect_key($data, array_flip($this->attributes)));
        }
    }

    /**
     * @return \App\Contracts\QueryBuilderInterface
     */
    protected function getQueryBuilder() : QueryBuilderInterface
    {
        $queryBuilder = new QueryBuilder($this->getTbName());

        return $queryBuilder->setModel($this);
    }

    /**
     * @param $data
     * @return static
     */
    public static function hydrate($data)
    {
        $model = new static((array) $data);

        return $model;
    }

    /**
     * @param array $items
     * @return array
     */
    public static function hydrateAll(array $items) : array
    {
        return array_map(function($item)
        {
            return static::hydrate($item);
        }, $items);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function findByID(int $id)
    {
        return  static::find([new DbCondition($this->getPrimaryKey(), '=', $id)])->firstOrFail();
    }

    /**
     * @param array $conditions
     * @return \App\Contracts\QueryBuilderInterface
     */
    public static function find(array $conditions) : QueryBuilderInterface
    {
        return (new static())->getQueryBuilder()
            ->select()
            ->setConditions($conditions);
    }

    /**
     * @param $variable
     * @return mixed|null
     */
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

    /**
     * @param $variable
     * @param $value
     */
    public function __set($variable, $value)
    {
        if(in_array($variable, $this->attributes)) {
            $this->data[$variable] = $value;
        } else {
            throw new WrongParameterException("Attribute ". $variable ." doesn't exist");
        }
    }

    /**
     * @param $variable
     * @return bool
     */
    public function __isset($variable)
    {
        if(!in_array($variable, $this->attributes)) {
            throw new WrongParameterException("Attribute ". $variable ." doesn't exist");
        }

        return isset($this->data[$variable]);
    }

    /**
     * @param $name
     */
    public final function __unset($name)
    {
        throw new WrongCallException("Forbidden action. Attributes list of ". get_called_class()
            . " is fixed: ". print_r($this->attributes, true));
    }
}
