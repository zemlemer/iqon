<?php

namespace App\Structures;

/**
 * Class DbCondition
 *
 * @package App\Structures
 */
class DbCondition
{
    /** @var string */
    public
        $field,
        $operator,
        $value;

    /**
     * DbCondition constructor.
     *
     * @param string $field
     * @param string $operator
     * @param string $value
     */
    public function __construct(string $field, string $operator, string $value)
    {
        $this->field = $field;
        $this->operator = $operator;
        $this->value = $value;
    }
}