<?php

declare(strict_types=1);

namespace Swis\JsonApi\Client\Providers;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Swis\JsonApi\Client\Client;
use Swis\JsonApi\Client\DocumentClient;
use Swis\JsonApi\Client\Facades\ResponseParserFacade;
use Swis\JsonApi\Client\Interfaces\ClientInterface;
use Swis\JsonApi\Client\Interfaces\DocumentClientInterface;
use Swis\JsonApi\Client\Interfaces\DocumentInterface;
use Swis\JsonApi\Client\Interfaces\DocumentParserInterface;
use Swis\JsonApi\Client\Interfaces\ResponseParserInterface;
use Swis\JsonApi\Client\Interfaces\TypeMapperInterface;
use Swis\JsonApi\Client\Parsers\DocumentParser;
use Swis\JsonApi\Client\Parsers\ResponseParser;
use Swis\JsonApi\Client\TypeMapper;

class ServiceProvider extends BaseServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__, 2).'/config/json-api-client.php',
            'json-api-client'
        );

        $this->registerSharedTypeMapper();
        $this->registerParsers();
        $this->registerClients();

        $this->registerLaravelHttpClientMacros();
    }

    public function boot()
    {
        $this->publishes([
            dirname(__DIR__, 2).'/config/' => config_path(),
        ], 'config');
    }

    protected function registerSharedTypeMapper()
    {
        $this->app->bind(TypeMapperInterface::class, TypeMapper::class);
        $this->app->singleton(TypeMapper::class);
    }

    protected function registerParsers()
    {
        $this->app->bind(DocumentParserInterface::class, DocumentParser::class);
        $this->app->bind(ResponseParserInterface::class, ResponseParser::class);
    }

    protected function registerClients()
    {
        $this->app->extend(
            ClientInterface::class,
            static function (ClientInterface $client) {
                if ($baseUri = config('json-api-client.base_uri')) {
                    $client->setBaseUri($baseUri);
                }

                return $client;
            }
        );

        $this->app->bind(ClientInterface::class, Client::class);
        $this->app->bind(DocumentClientInterface::class, DocumentClient::class);
    }

    protected function registerLaravelHttpClientMacros(): void
    {
        if (!class_exists(Http::class)) {
            // N.B. The HTTP Client was introduced in Laravel 7.
            return;
        }

        if (!PendingRequest::hasMacro('asJsonApi')) {
            PendingRequest::macro('asJsonApi', function (): PendingRequest {
                /* @var \Illuminate\Http\Client\PendingRequest $this */
                return $this->bodyFormat('json')
                    ->contentType('application/vnd.api+json')
                    ->accept('application/vnd.api+json');
            });
        }

        if (!Response::hasMacro('jsonApi')) {
            Response::macro('jsonApi', function (): DocumentInterface {
                /* @var \Illuminate\Http\Client\Response $this */
                return ResponseParserFacade::parse($this->toPsrResponse());
            });
        }
    }
}
