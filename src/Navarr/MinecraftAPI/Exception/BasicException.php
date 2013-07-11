<?php

namespace Navarr\MinecraftAPI\Exception;

class BasicException extends \Exception
{
    public function __construct($message = "User not premium")
    {
        parent::__construct($message);
    }
}
