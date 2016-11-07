<?php

namespace App\DB;

use App\Contracts\DbInterface;
use App\Contracts\QueryBuilderInterface;
use App\Exceptions\ResourceNotFoundException;
use App\Exceptions\WrongCallException;
use App\Models\BaseModel;
use App\Structures\DbCondition;
use App\Structures\DBExpression;

class QueryBuilder implements QueryBuilderInterface
{
    const
        COMMAND_SELECT = "select",
        COMMAND_UPDATE = "update",
        COMMAND_INSERT = "insert",
        COMMAND_DELETE = "delete",
        COMMAND_REPLACE = "replace";

    /**
     * @var DbInterface $provider
     */
    static protected $provider;

    protected $sql = '';
    protected $baseSQL = '';
    protected $bindings = [];

    /** @var BaseModel */
    protected $model;

    /** @var array|DbCondition[] $conditions */
    protected $conditions = [];
    protected $tableName;
    protected $result;

    protected $command;
    protected $select = [];
    protected $where = [];
    protected $orderBy = [];
    protected $keys = [];
    protected $values = [];


    public function __construct(string $tableName)
    {
        $this->tableName = $tableName;
    }

    public function setModel($model) : QueryBuilderInterface
    {
        $this->model = $model;

        return $this;
    }

    public static function setProvider(DbInterface $provider)
    {
        static::$provider = $provider;
    }

    protected function getProvider() : DbInterface
    {
        if(!static::$provider) {
            throw new WrongCallException("Provider isn't set");
        }

        return static::$provider;
    }

    public function select(array $fields = ['*']) : QueryBuilderInterface
    {
        $this->baseSQL = "select ". implode(',', $fields) ." from ". $this->tableName;
        $this->command = static::COMMAND_SELECT;

        return $this;
    }

    public function replace(array $fields) : QueryBuilderInterface
    {
        $this->baseSQL = "replace into ". $this->tableName ." ("
            . implode(',', array_keys($fields)) .") values(:"
            . implode(',:', array_keys($fields)) .")";
        foreach ($fields as $field => $value) {
            $this->bindings[':'. $field] = $value;
        }

        $this->command = static::COMMAND_REPLACE;

        return $this;
    }

    public function update(array $fields) : QueryBuilderInterface
    {
        $updateChunks = [];

        foreach ($fields as $field => $value) {

            if ($value instanceof DBExpression) {
                $updateChunks[] = $field . " = " . $value;
            } else {
                $this->bindings[':'. $field] = $value;
                $updateChunks[] = $field . " = :" . $field;
            }
        }

        $this->baseSQL = "update " . $this->tableName . " set "
            . implode(', ', $updateChunks);

        $this->command = static::COMMAND_UPDATE;

        return $this;
    }

    public function delete() : QueryBuilderInterface
    {
        $this->baseSQL = "delete from " . $this->tableName;

        $this->command = static::COMMAND_DELETE;

        return $this;
    }

    /**
     * @param string $field
     * @param bool $desc
     * @return QueryBuilderInterface
     */
    public function orderBy(string $field, bool $desc = false) : QueryBuilderInterface
    {
        if($this->command !== static::COMMAND_SELECT) {
            throw new WrongCallException("Ordering for command '". $this->command ."' isn't supported");
        }

        $this->orderBy[$field] = $desc;

        return $this;
    }

    public function setConditions(array $conditions) : QueryBuilderInterface
    {
        foreach ($conditions as $condition) {
            $this->where($condition);
        }

        return $this;
    }

    public function where($condition) : QueryBuilderInterface
    {
        $this->conditions[] = $condition;

        return $this;
    }

    // Не стал реализовывать здесь limit,
    // что в боевой версии, конечно, следовало бы сделать
    public function first()
    {
        $list = $this->get();

        if($list) {
            list($result) = $list;

            if ($this->model) {
                return $this->model->hydrate($result);
            }

            return $result;
        } else {
            return null;
        }
    }

    public function firstOrFail()
    {

        $result = $this->first();
        if(is_null($result)) {
            throw new ResourceNotFoundException("Can't get first by conditions: ". print_r($this->conditions, true));
        }

        return $result;
    }

    public function all() : array
    {
        $result = $this->get();

        if($result && $this->model) {
            return $this->model->hydrateAll($result);
        }

        return $result;
    }

    public function get() : array
    {
        $this->execute();

        return $this->result ? $this->result : [];
    }

    public function execute()
    {
        $this->buildSQL();

        if(!$this->sql) {
            throw new WrongCallException("Query isn't ready");
        }

        $this->result = $this->getProvider()->query($this->sql, $this->bindings);

        if($this->command === static::COMMAND_REPLACE) {
            return $this->getProvider()->getLastID();
        }

        return false;
    }

    protected function buildSQL() : self
    {
        $this->sql = $this->baseSQL;

        if($this->conditions) {
            $whereChunks = [];
            foreach ($this->conditions as $condition) {
                if ($condition instanceof DBExpression) {
                    $whereChunks[] = $condition;
                } else {
                    $whereChunks[] = "`". $condition->field ."` ". $condition->operator ." :". $condition->field;
                    $this->bindings[':'. $condition->field] = $condition->value;
                }
            }
            $this->sql .= " where ". implode(' and ', $whereChunks);
        }

        if($this->orderBy) {
            $orderChunks = [];
            foreach ($this->orderBy as $field => $desc) {
                $orderChunks[] = $field .' '. ($desc ? 'desc' : 'asc');
            }
            $this->sql .= " order by ". implode(', ', $orderChunks);
        }

        return $this;
    }
}
