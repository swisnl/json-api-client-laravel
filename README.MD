# { json:api } Client Laravel

[![PHP from Packagist](https://img.shields.io/packagist/php-v/swisnl/json-api-client-laravel.svg)](https://packagist.org/packages/swisnl/json-api-client-laravel)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/swisnl/json-api-client-laravel.svg)](https://packagist.org/packages/swisnl/json-api-client-laravel)
[![Software License](https://img.shields.io/packagist/l/swisnl/json-api-client-laravel.svg)](LICENSE)
[![Build Status](https://travis-ci.com/swisnl/json-api-client-laravel.svg?branch=master)](https://travis-ci.com/swisnl/json-api-client-laravel)
[![Scrutinizer Coverage](https://img.shields.io/scrutinizer/coverage/g/swisnl/json-api-client-laravel.svg)](https://scrutinizer-ci.com/g/swisnl/json-api-client-laravel/?branch=master)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/swisnl/json-api-client-laravel.svg)](https://scrutinizer-ci.com/g/swisnl/json-api-client-laravel/?branch=master)
[![Made by SWIS](https://img.shields.io/badge/%F0%9F%9A%80-made%20by%20SWIS-%23D9021B.svg)](https://www.swis.nl)

This package contains some Laravel specific classes to make it easier to use [swisnl/json-api-client](https://github.com/swisnl/json-api-client) with Laravel.

## Installation

``` bash
composer require swisnl/json-api-client-laravel
```

N.B. Make sure you have installed a PSR-18 HTTP Client and PSR-17 HTTP Factories before you install this package or install one at the same time e.g. `composer require swisnl/json-api-client guzzlehttp/guzzle:^7.3`.

### HTTP Client

We are decoupled from any HTTP messaging client with the help of [PSR-18 HTTP Client](https://www.php-fig.org/psr/psr-18/) and [PSR-17 HTTP Factories](https://www.php-fig.org/psr/psr-17/).
This requires an extra package providing [psr/http-client-implementation](https://packagist.org/providers/psr/http-client-implementation) and [psr/http-factory-implementation](https://packagist.org/providers/psr/http-factory-implementation).
To use Guzzle 7, for example, simply require `guzzlehttp/guzzle`:

``` bash
composer require guzzlehttp/guzzle:^7.3
```

See [Bind clients](#bind-clients) if you want to use your own HTTP client or use specific configuration options.

### Service Provider and Facade Aliases

The required service provider and some facade aliases are automatically discovered by Laravel.
However, if you've disabled package auto discover, you must add the service provider and optionally the facade aliases to your `config/app.php` file:

``` php
'providers' => [
    ...,
    \Swis\JsonApi\Client\Providers\ServiceProvider::class,
],
'aliases' => [
    ...,
    'DocumentFactory' => \Swis\JsonApi\Client\Facades\DocumentFactoryFacade::class,
    'DocumentParser' => \Swis\JsonApi\Client\Facades\DocumentParserFacade::class,
    'ItemHydrator' => \Swis\JsonApi\Client\Facades\ItemHydratorFacade::class,
    'ResponseParser' => \Swis\JsonApi\Client\Facades\ResponseParserFacade::class,
    'TypeMapper' => \Swis\JsonApi\Client\Facades\TypeMapperFacade::class,
],
```

### Configuration

The following is the default configuration provided by this package:

``` php
return [
    /*
    |--------------------------------------------------------------------------
    | Base URI
    |--------------------------------------------------------------------------
    |
    | Specify a base uri which will be prepended to every URI.
    |
    | Default: empty string
    |
    */
    'base_uri' => '',
];
```
        
If you would like to make changes to the default configuration, publish and edit the configuration file:

``` bash
php artisan vendor:publish --provider="Swis\JsonApi\Client\Providers\ServiceProvider" --tag="config"
```


## Getting started

Simply let the container inject the `DocumentClient` and you're good to go!

``` php
use Swis\JsonApi\Client\DocumentClient;

class RecipeController extends Controller
{
    public function index(DocumentClient $client)
    {
        $document = $client->get('https://cms.contentacms.io/api/recipes');
    
        /** @var \Swis\JsonApi\Client\Collection&\Swis\JsonApi\Client\Item[] $recipes */
        $recipes = $document->getData();
        
        foreach ($recipes as $recipe) {
            // Do stuff with the recipe
        }
    }
}
```

Take a look at [swisnl/json-api-client](https://github.com/swisnl/json-api-client) for more usage information.


## Service Provider

The `\Swis\JsonApi\Client\Providers\ServiceProvider` registers the `TypeMapper`, `JsonApi\Parser` and both clients; `Client` and `DocumentClient`.
Each section can be overwritten to allow extended customization.

### Bind TypeMapper

The service provider registers the `\Swis\JsonApi\Client\TypeMapper` as a singleton so your entire application has the same mappings available.

#### Mapping types

You can manually register items with the `TypeMapper` or use the supplied `TypeMapperServiceProvider`:

``` php
use Swis\JsonApi\Client\Providers\TypeMapperServiceProvider as BaseTypeMapperServiceProvider;

class TypeMapperServiceProvider extends BaseTypeMapperServiceProvider
{
    /**
     * A list of class names implementing \Swis\JsonApi\Client\Interfaces\ItemInterface.
     *
     * @var string[]
     */
    protected $items = [
        \App\Items\Author::class,
        \App\Items\Blog::class,
        \App\Items\Comment::class,
    ];
}
```

### Bind Clients

The service provider registers the `Client` and `DocumentClient` to your application.
By default the `Client` uses [php-http/discovery](https://github.com/php-http/discovery) to find an available HTTP client, request factory and stream factory so you don't have to setup those yourself.
You can specify your own HTTP client, request factory or stream factory by customizing the container binding.
This is a perfect way to add extra options to your HTTP client or register a mock HTTP client for your tests:

``` php
class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        $this->app->bind(\Swis\JsonApi\Client\Client::class, function ($app) {
            if ($app->environment('testing')) {
                $httpClient = new \Swis\Http\Fixture\Client(
                    new \Swis\Http\Fixture\ResponseBuilder('/path/to/fixtures')
                );
            } else {
                $httpClient = new \GuzzleHttp\Client(
                    [
                        'http_errors' => false,
                        'timeout' => 2,
                    ]
                );
            }
    
            return new \Swis\JsonApi\Client\Client($httpClient);
        });
    }
}
```

N.B. This example uses our [swisnl/php-http-fixture-client](https://github.com/swisnl/php-http-fixture-client) when in testing environment.
This package allows you to easily mock requests with static fixtures.
Definitely worth a try!


## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.


## Testing

``` bash
composer test
```


## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.


## Security

If you discover any security related issues, please email security@swis.nl instead of using the issue tracker.


## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.


## SWIS :heart: Open Source

[SWIS](https://www.swis.nl) is a web agency from Leiden, the Netherlands. We love working with open source software.
