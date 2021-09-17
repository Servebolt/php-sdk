# Servebolt PHP SDK

A PHP-based software development kit to access the [Servebolt API](https://docs.servebolt.io/).

## Get started
You can install **servebolt-php-sdk** via composer or by downloading the source.

### Via Composer:
**servebolt-php-sdk** is available on Packagist as the
[`servebolt/sdk`](https://packagist.org/packages/servebolt/sdk) package:
```
composer require servebolt/sdk
```

### From source:
You can also build the source code yourself.
1. Check out source: ```git checkout git@github.com:Servebolt/php-sdk.git```<br>
2. Navigate into directory: `cd php-sdk`
3. Run `composer install`

## Usage

### Initialization

Remember to include the Composer autoload-file!

```php
require __DIR__ . '/vendor/autoload.php';
use Servebolt\Sdk\Client;
$client = new Client([
    'apiToken' => 'your-api-token',
]);
$response = $client->cron->list(); // Example call - get all cron jobs
```

## Documentation
Please visit our [documentation](https://php-sdk.dev.servebolt.com/) for further details.
