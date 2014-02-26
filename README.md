Config
======

Config for Start PHP

[2014/01/30] add data base access point by PDO.



Examples
=

**Composer install:**

Add this in your composer.json:

    "require": {
        "neos/data": "dev-master"
    }
    
Now, type in terminal:

    $ composer update


**Optional condition to make things easier!**

    class_alias('Neos\Data\Config', 'o');
    class_alias('Neos\Data\Conn', 'DB');
    
*Or, in class with namespaces, type (in top):*

    use Neos\Data\Config as o;
    use Neos\Data\Conn as DB;

**Configurations**
    
    //loading config file
    o::load('path/to/config/ini/type/file.ini');
    
    //usage
    print_r( o::item() ); //item in config file.
    
    //saving
    o::save(); //or o::save('path/to/copy/config/ini/type/file.ini');

**Data Base**

    $value = 01;
    $db = (new DB())->query('SELECT * FROM Table WHERE FielN = :value', ['value'=>$value]);
    
    //Dump
    print_r($db->getLine(0));
    
**Setting Alias for a connection database**

***in Config.ini***

    [db]
    default = "dev";
    
    dev[type] = "mysql";
    dev[host] = "localhost";
    dev[user] = "user";
    dev[password] = "*******";
    dev[database] = "database";
    dev[charset] = "utf8";
    
    other[type] = "sqlite";
    other[database] = PPHP"app.db";

***PHP script***
 
    $db = new DB(); //connecting in a default database (dev)
    //or
    $db = DB('other'); //for connect in 'other' (sqlite)
    
