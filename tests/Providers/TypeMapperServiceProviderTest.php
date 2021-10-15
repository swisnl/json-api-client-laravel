<?php

declare(strict_types=1);

namespace Swis\JsonApi\Client\Tests\Providers;

use Swis\JsonApi\Client\Tests\AbstractTest;
use Swis\JsonApi\Client\Tests\Mocks\Items\ChildItem;
use Swis\JsonApi\Client\Tests\Mocks\Items\MasterItem;
use Swis\JsonApi\Client\Tests\Mocks\MockTypeMapperServiceProvider;
use Swis\JsonApi\Client\TypeMapper;

class TypeMapperServiceProviderTest extends AbstractTest
{
    /**
     * @test
     */
    public function itRegistersTypesWithTheTypeMapper()
    {
        $provider = new MockTypeMapperServiceProvider($this->app);
        $typeMapper = $this->createMock(TypeMapper::class);
        $typeMapper->expects($this->exactly(2))
            ->method('setMapping')
            ->withConsecutive(
                ['child', ChildItem::class],
                ['master', MasterItem::class]
            );

        $provider->boot($typeMapper);
    }
}
