# GetLocalization

GetLocalization is a simple php client for the [GetLocalization File Management api](http://www.getlocalization.com/library/api/get-localization-file-management-api/)

[![Build Status](https://secure.travis-ci.org/nicmart/Functionals.png?branch=master)](http://travis-ci.org/nicmart/GetLocalization)

## Install

The best way to install GetLocalization is [through composer](http://getcomposer.org).

Just create a composer.json file for your project:

```JSON
{
    "require": {
        "nicmart/getlocalization": "dev-master"
    }
}
```

Then you can run these two commands to install it:

    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar install

or simply run `composer install` if you have have already [installed the composer globally](http://getcomposer.org/doc/00-intro.md#globally).

Then you can include the autoloader, and you will have access to the library classes:

```php
<?php
require 'vendor/autoload.php';

use GetLocalization\Client;
```

## Initialization

All you have to do is to instantiate a new Client object, passing to its constructor your GetLocalization username and
password and the name of your project:

```php
<?php
    $client = new GetLocalization\Client('username', 'password', 'projectname');
```

## Api calls
This library offers an one-to-one mapping with the
[GetLocalization File Management API](http://www.getlocalization.com/library/api/get-localization-file-management-api/)

### Managing master files


CREDITS
-----
* This library has been written in [Comperio srl](http://www.comperio.it) to manage community driven localizations
 for [ClavisNG](http://www.comperio.it/soluzioni/clavisng/un-gestionale-per-reti-di-biblioteche/)
 and [DiscoveryNG](http://www.comperio.it/soluzioni/discoveryng/panoramica/)
* We use the [Guzzle Http framework](http://guzzlephp.org/) for sending and recieve http requests.

Tests
-----

    $ phpunit

License
-------
MIT, see LICENSE.

