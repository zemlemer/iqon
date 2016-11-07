<?php

namespace App\Contracts;

interface ResponseInterface
{
    public function __construct(int $code);

    public function __toString() : string;

    public function sendHeaders() : self;

    public function setBody(string $code) : self;
}