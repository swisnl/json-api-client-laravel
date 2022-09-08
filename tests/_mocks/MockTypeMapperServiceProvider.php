<?php

declare(strict_types=1);

namespace Swis\JsonApi\Client\Tests\Mocks;

use Swis\JsonApi\Client\Providers\TypeMapperServiceProvider;
use Swis\JsonApi\Client\Tests\Mocks\Items\ChildItem;
use Swis\JsonApi\Client\Tests\Mocks\Items\ParentItem;

class MockTypeMapperServiceProvider extends TypeMapperServiceProvider
{
    protected $items = [
        ChildItem::class,
        ParentItem::class,
    ];
}
