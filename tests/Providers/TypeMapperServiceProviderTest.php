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
            ->willReturnCallback(function (string $type, string $class) {
                static $i = 0;
                switch (++$i) {
                    case 1:
                        $this->assertEquals('child', $type);
                        $this->assertEquals(ChildItem::class, $class);
                        break;
                    case 2:
                        $this->assertEquals('parent', $type);
                        $this->assertEquals(ParentItem::class, $class);
                        break;
                }
            });

        $provider->boot($typeMapper);
    }
}
