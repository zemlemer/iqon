<?php

namespace App\Structures;

class DbCondition
{
    public
        $field,
        $operator,
        $value;

    public function __construct(string $field, string $operator, string $value)
    {
        $this->field = $field;
        $this->operator = $operator;
        $this->value = $value;
    }
}