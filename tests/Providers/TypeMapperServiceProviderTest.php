<?php

declare(strict_types=1);

namespace Swis\JsonApi\Client\Tests\Providers;

use Swis\JsonApi\Client\Tests\AbstractTestCase;
use Swis\JsonApi\Client\Tests\Mocks\Items\ChildItem;
use Swis\JsonApi\Client\Tests\Mocks\Items\ParentItem;
use Swis\JsonApi\Client\Tests\Mocks\MockTypeMapperServiceProvider;
use Swis\JsonApi\Client\TypeMapper;

class TypeMapperServiceProviderTest extends AbstractTestCase
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
                ['parent', ParentItem::class]
            );

        $provider->boot($typeMapper);
    }
}
