<?php

namespace Navarr\MinecraftAPI\Exception;

class MigrationException extends \Exception
{
    public function __construct($message = 'Migrated Account')
    {
        parent::__construct($message);
    }
}
