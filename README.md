# Servebolt PHP SDK

A PHP-based software development kit to access the Servebolt API.

## Get started
You can install **servebolt-php-sdk** via composer or by downloading the source.

### Via Composer:
**servebolt-php-sdk** is available on Packagist as the
[`servebolt/sdk`](https://packagist.org/packages/servebolt/sdk) package:
```
composer require servebolt/sdk
```

## Usage

### Initialization

Remember to include the Composer autoload-file!

```php
require __DIR__ . '/vendor/autoload.php';
use Servebolt\Sdk\Client;
$client = new Client([
    'apiToken' => 'your-api-token',
]);
```

<a name="additional-instantiation-example"></a>An example with additional configuration options (using default values):

```php
use Servebolt\Sdk\Client;
$client = new Client([
    'apiToken' => 'your-api-token',
    
    // Available authentication drivers: apiToken
    'authDriver' => 'apiToken',
    
    // Override API URL
    'baseUri' => 'https://api.servebolt.io/v1/',
    
    // Whether to throw exceptions whenever a 4xx HTTP error occurs during a request
    'throwExceptionsOnClientError' => false,
    
    // Decide how you want the SDK to respond after querying the API
    // Options: customResponse, psr7, decodedBody
    'responseObjectType' = 'customResponse',
]);
```

### API Authentication
As of now the API only supports bearer token authentication, and hence only one authentication driver available in the SDK - "apiToken". 

#### How to obtain an API token
1. Go to [admin.servebolt.com](https://admin.servebolt.com/)
2. Go to the desired bolt
3. Go to the desired site
4. Click the API-tab
5. Use the token and environment Id visible in the form

### API documentation
If you want to do your own testing outside the SDK you can check out our [API-documentation](https://docs.servebolt.io/).

## Endpoints
This SDK is coupled with the Servebolt API and has corresponding methods in this SDK.

### Environment

The environment endpoint contains only the cache purge for the time being.

#### Cache purge
The cache purge call will return an instance of the [Response-object](#response-object).
```php
$environmentId = 123;
$files = [
    'https://example.com/some/path.html',
    'https://example.com/some/other/path.html',
];
$prefixes = [
    'https://example.com/some/prefix/path',
    'https://example.com/some/other/prefix/path',
];
$client->environment->purgeCache($environmentId, $files, $prefixes);
```

### Cron

Cron jobs can be fully managed through the API and SDK.<br>
TODO: Document the Cron endpoint when methods are done.

## <a name="response-object"></a>Response object
We've created a unified response object that will get returned from all methods in the SDK that communicates with the API. Using this object is default behaviour, but it can be changed by using the "responseObjectType" configuration option when [initializing the client](#additional-instantiation-example).

### Example

```php
use Servebolt\Sdk\Client;
$client = new Client(['apiToken'   => 'your-api-token']);
$response = $client->cron->list();
if ($response->hasErrors()) {
    $errors = $response->getErrors();
    // Display errors
}

if ($response->wasSuccessful()) {
    if ($response->hasMessages()) {
        $errors = $response->getMessages();
        // Display messages
    } else {
        // Display general success
    }
}
```

### Available methods

```php
$response->getStatusCode() : bool // Get HTTP status code (if present)
```

```php
$response->wasSuccessful() : bool
```

```php
$response->getRawResponse() : object // Use this to get the JSON decoded response from the HTTP request
```

```php
$response->isMultiple() : bool
```

```php
$response->isIterable() : bool // Alias of "isMultiple"
```

```php
$response->hasResult() : bool
```

```php
$response->getResultItems()
```

```php
$response->getFirstResultItem()
```

#### Message handling

```php
$response->hasMessages() : bool
```

```php
$response->getMessages() : array
```

```php
$response->hasSingleMessage() : bool
```

```php
$response->getFirstMessage() : array
```

#### Error handling

```php
$response->hasErrors() : bool
```

```php
$response->getErrors() : array
```

```php
$response->hasSingleError() : bool
```

```php
$response->getFirstError()
```

```php
$response->getFirstErrorMessage()
```

```php
$response->getFirstErrorCode()
```
