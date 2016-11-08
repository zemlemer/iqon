<?php

namespace App\Contracts;

/**
 * Interface ResponseInterface
 *
 * @package App\Contracts
 */
interface ResponseInterface
{
    /**
     * ResponseInterface constructor.
     *
     * @param int $code
     */
    public function __construct(int $code);

    /**
     * @return string
     */
    public function __toString() : string;

    /**
     * @return \App\Contracts\ResponseInterface
     */
    public function sendHeaders() : self;

    /**
     * @param string $code
     * @return \App\Contracts\ResponseInterface
     */
    public function setBody(string $code) : self;
}
