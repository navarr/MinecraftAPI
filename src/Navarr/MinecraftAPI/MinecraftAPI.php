<?php

namespace Navarr\MinecraftAPI;

use Navarr\MinecraftAPI\Exception\BadLoginException;
use Navarr\MinecraftAPI\Exception\MigrationException;
use Navarr\MinecraftAPI\Exception\BasicException;

class MinecraftAPI
{
    protected $username;
    protected $sessionID;

    const API_URL = "https://login.minecraft.net/";

    public function __construct($username = null, $password = null)
    {
        if ($username !== null && $password !== null) {
            $this->login($username, $password);
        }
    }

    public function login($username, $password, $version = 13)
    {
        $user = urlencode($username);
        $pass = urlencode($password);

        $output = file_get_contents(static::API_URL . "?user={$user}&password={$pass}&version={$version}");

        if (strpos($output, 'Bad login') !== false) {
            throw new BadLoginException();
        }
        if (strpos($output, 'Account migrated') !== false) {
            throw new MigrationException();
        }
        if (strpos($output, 'not premium') !== false) {
            throw new BasicException();
        }

        $values = explode(":", $output);

        $this->username = $values[2];
        $this->sessionID = $values[3];

        return true;
    }

    public function __get($var)
    {
        if ($var == 'username') {
            return $this->username;
        }
        if ($var == 'sessionID') {
            return $this->sessionID;
        }
        return null;
    }
}
