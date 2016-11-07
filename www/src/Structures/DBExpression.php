<?php

namespace App\Structures;

class DBExpression
{
    protected $expression;

    public function __construct(string $expression)
    {
        $this->expression = $expression;
    }

    public function __toString()
    {
        return $this->expression;
    }
}
