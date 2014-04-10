<?php

namespace Navarr\MinecraftAPI;

use Navarr\MinecraftAPI\Exception\BadLoginException;
use Navarr\MinecraftAPI\Exception\MigrationException;
use Navarr\MinecraftAPI\Exception\BasicException;

/**
 * Class MinecraftAPI
 * @package Navarr\MinecraftAPI
 * @property string $username
 * @property string $accessToken
 * @property string $minecraftID
 */
class MinecraftAPI
{
    /** @var String Current Minecraft username */
    protected $username;
    /** @var String Mojang Account Access Token */
    protected $accessToken;
    /** @var String Minecraft Profile UUID */
    protected $minecraftID;

    const API_URL = "https://authserver.mojang.com/authenticate";

    public function __construct($username = null, $password = null)
    {
        if ($username !== null && $password !== null) {
            $this->login($username, $password);
        }
    }

    public function login($username, $password)
    {
        $postdata = json_encode(
            [
                'agent' => [
                    'name' => 'Minecraft',
                    'version' => 1,
                ],
                'username' => $username,
                'password' => $password,
            ]
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, static::API_URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-type: application/json']);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__).DIRECTORY_SEPARATOR."cacert.pem");
        $output = curl_exec($ch);

        $info = curl_getinfo($ch);

        $error = curl_error($ch);

        curl_close($ch);

        $outputObject = json_decode($output);

        if ($output === false) {
            throw new \RuntimeException('Error communicating with server');
        }

        if (isset($outputObject->error)) {
            if (isset($outputObject->cause) && $outputObject->cause == "UserMigratedException") {
                throw new MigrationException;
            }
            if ($outputObject->error == "ForbiddenOperationException") {
                throw new BadLoginException;
            }
            throw new \RuntimeException('Error communicating with server');
        }

        if (!isset($outputObject->selectedProfile)) {
            throw new BasicException;
        }

        $this->username = $outputObject->selectedProfile->name;
        $this->accessToken = $outputObject->accessToken;
        $this->minecraftID = $outputObject->selectedProfile->id;

        return true;
    }

    public function __get($var)
    {
        if ($var == 'username') {
            return $this->username;
        }
        if ($var == 'accessToken') {
            return $this->accessToken;
        }
        if ($var == 'minecraftID') {
            return $this->minecraftID;
        }
        return null;
    }
}
