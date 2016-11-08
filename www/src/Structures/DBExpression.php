<?php

namespace App\Structures;

/**
 * Class DBExpression
 *
 * @package App\Structures
 */
class DBExpression
{
    /** @var string */
    protected $expression;

    /**
     * DBExpression constructor.
     *
     * @param string $expression
     */
    public function __construct(string $expression)
    {
        $this->expression = $expression;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->expression;
    }
}
