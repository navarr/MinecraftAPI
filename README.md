MinecraftAPI
============

An API for validating a user against Mojang's servers written in PHP

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

I, Navarr Barnier, release this program to the public domain - except where prohibited by law.  You may use this program in any
way, shape, or form.  Sell it, I guess? Anything.  Credit would be nice, but is not required.  No warranty is expressed or
implied in this code, and I am not to be held responsible for any issues that arise from it.
