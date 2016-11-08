<?php

namespace App\Contracts;


/**
 * Interface QueryBuilderInterface
 *
 * @package App\Contracts
 */
interface QueryBuilderInterface
{
    /**
     * QueryBuilderInterface constructor.
     *
     * @param string $tableName
     */
    public function __construct(string $tableName);

    /**
     * @param \App\Contracts\DbInterface $provider
     * @return mixed
     */
    public static function setProvider(DbInterface $provider);

    /**
     * @param $model
     * @return \App\Contracts\QueryBuilderInterface
     */
    public function setModel($model) : self;

    /**
     * @param array $fields
     * @return \App\Contracts\QueryBuilderInterface
     */
    public function select(array $fields = []) : self;

    /**
     * @param array $fields
     * @return \App\Contracts\QueryBuilderInterface
     */
    public function update(array $fields) : self;

    /**
     * @param array $data
     * @return \App\Contracts\QueryBuilderInterface
     */
    public function replace(array $data) : self;

    /**
     * @return \App\Contracts\QueryBuilderInterface
     */
    public function delete() : self;

    /**
     * @param array $data
     * @return \App\Contracts\QueryBuilderInterface
     */
    public function setConditions(array $data) : self;

    /**
     * @param $condition
     * @return \App\Contracts\QueryBuilderInterface
     */
    public function where($condition) : self;

    /**
     * @param string $field
     * @param bool $desc
     * @return \App\Contracts\QueryBuilderInterface
     */
    public function orderBy(string $field, bool $desc = false) : self;

    /**
     * @return mixed
     */
    public function execute();

    /**
     * @return array
     */
    public function get() : array;

    /**
     * @return mixed
     */
    public function first();

    /**
     * @return mixed
     */
    public function firstOrFail();

    /**
     * @return array
     */
    public function all() : array;
}

