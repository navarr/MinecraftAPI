<?php

namespace Navarr\MinecraftAPI\Exception;

class BadLoginException extends \Exception
{
    public function __construct($message = "Bad Login")
    {
        parent::__construct($message);
    }
}
