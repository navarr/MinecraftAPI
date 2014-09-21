MinecraftAPI
============

An API for validating a user against Mojang's servers written in PHP

## Installation

Assuming you have [composer](http://www.getcomposer.org/),

`php composer.phar require navarr/minecraft-api:2.*`

## How to use

Using the MinecraftAPI is incredibly easy in PHP.  Assuming that you have it installed via composer,

```php
<?php
    // composer autoload call
    
    use Navarr\MinecraftAPI\MinecraftAPI;
    use Navarr\MinecraftAPI\Exception\BadLoginException;
    use Navarr\MinecraftAPI\Exception\MigrationException;
    use Navarr\MinecraftAPI\Exception\BasicException;
    
    try {
        $minecraftApi = new MinecraftAPI($username, $password);
    } catch (BadLoginException $e) {
        // Invalid Credentials
    } catch (MigrationException $e) {
        // User tried to login with their minecraft name instead of their mojang account handle (and have migrated)
    } catch (BasicException $e) {
        // The account is a "basic" Minecraft account.  They have not purchased Minecraft.
    }
    
    $minecraftName = $minecraftApi->username;
    $accessToken = $minecraftApi->accessToken;
    $uuid = $minecraftApi->minecraftID;
```

## License

MIT License (coming soon!)
