<?php

namespace App\Contracts;


interface QueryBuilderInterface
{
    public function __construct(string $tableName);

    public static function setProvider(DbInterface $provider);

    public function setModel($model) : self;

    public function select(array $fields = []) : self;

    public function update(array $fields) : self;

    public function replace(array $data) : self;

    public function delete() : self;

    public function setConditions(array $data) : self;

    public function where($condition) : self;

    public function orderBy(string $field, bool $desc = false) : self;

    public function execute();

    public function get() : array;

    public function first();

    public function firstOrFail();

    public function all() : array;
}
