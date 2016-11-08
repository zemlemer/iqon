<?php

namespace App\Http;

use App\Contracts\ResponseInterface;
use App\Exceptions\WrongParameterException;

/**
 * Class Response
 *
 * @package App\Http
 */
class Response implements ResponseInterface
{
    const
        CODE_OK = 200,
        CODE_NOT_FOUND= 404,
        HEADERS = [
            self::CODE_OK => 'OK',
            self::CODE_NOT_FOUND => 'Not Found',
    ];

    /** @var int */
    protected $code;

    /** @var */
    protected $body;

    /** @var array */
    protected $data = [];

    /**
     * Response constructor.
     *
     * @param int $code
     */
    public function __construct(int $code = self::CODE_OK)
    {
        if(!array_key_exists($code, static::HEADERS)) {
            throw new WrongParameterException("Code ". $code ." isn't set");
        }

        $this->code = $code;
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return $this->body;
    }

    /**
     * @return \App\Contracts\ResponseInterface
     */
    public function sendHeaders() : ResponseInterface
    {
        header("HTTP/1.0 ". $this->code . " ". static::HEADERS[$this->code]);

        return $this;
    }

    /**
     * @param string $code
     * @return \App\Contracts\ResponseInterface
     */
    public function setBody(string $code) : ResponseInterface
    {
        $this->body = $code;

        return $this;
    }
}
