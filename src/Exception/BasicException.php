<?php

namespace Navarr\MinecraftAPI\Exception;

class BasicException extends \Exception
{
    public function __construct($message = 'User does not have a Minecraft account')
    {
        parent::__construct($message);
    }
}
