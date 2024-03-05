<?php

declare(strict_types=1);

namespace Swis\JsonApi\Client\Tests\Providers;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Swis\JsonApi\Client\Document;
use Swis\JsonApi\Client\Tests\AbstractTestCase;

class ServiceProviderTest extends AbstractTestCase
{
    protected function setUp(): void
    {
        if (!class_exists(Http::class)) {
            $this->markTestSkipped('The Laravel HTTP Client is not available.');
        }

        parent::setUp();
    }

    /**
     * @test
     */
    public function itRegistersHttpFacadeMacroForRequest(): void
    {
        // arrange
        Http::fake();

        // act
        Http::asJsonApi()->get('https://example.com');

        // assert
        Http::assertSent(static function (Request $request) {
            return $request->hasHeader('Content-Type', 'application/vnd.api+json')
                && $request->hasHeader('Accept', 'application/vnd.api+json');
        });
    }

    /**
     * @test
     */
    public function itRegistersHttpFacadeMacroForResponse(): void
    {
        // arrange
        Http::fake();

        // act
        $data = Http::get('https://example.com')->jsonApi();

        // assert
        $this->assertInstanceOf(Document::class, $data);
    }
}
