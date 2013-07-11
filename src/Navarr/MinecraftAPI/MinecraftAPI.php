<?php

namespace Navarr\MinecraftAPI;

use Navarr\MinecraftAPI\Exception\BadLoginException;
use Navarr\MinecraftAPI\Exception\MigrationException;
use Navarr\MinecraftAPI\Exception\BasicException;

/**
 * Class MinecraftAPI
 * @package Navarr\MinecraftAPI
 * @property string $username
 * @property string $sessionID
 * @property string $minecraftID
 */
class MinecraftAPI
{
    protected $username;
    protected $sessionID;
    protected $minecraftID;

    const API_URL = "https://login.minecraft.net/";

    public function __construct($username = null, $password = null)
    {
        if ($username !== null && $password !== null) {
            $this->login($username, $password);
        }
    }

    public function login($username, $password, $version = 14)
    {
        $postdata = http_build_query(
            [
                'user' => $username,
                'version' => $version,
                'password' => $password,
            ]
        );
        $opts = [
            'http' => [
                'method' => 'POST',
                'header' => 'Content-type: application/x-www-form-urlencoded',
                'content' => $postdata,
            ]
        ];
        $output = file_get_contents(static::API_URL, false, stream_context_create($opts));

        if (strpos($output, 'Bad Request') !== false || strpos($output, 'Old version') !== false) {
            throw new \RuntimeException('API Out of Date');
        }
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
        $this->minecraftID = $values[4];

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
        if ($var == 'minecraftID') {
            return $this->minecraftID;
        }
        return null;
    }
}
