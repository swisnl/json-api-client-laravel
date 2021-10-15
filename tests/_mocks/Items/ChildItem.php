<?php

declare(strict_types=1);

namespace Swis\JsonApi\Client\Tests\Mocks\Items;

use Swis\JsonApi\Client\Item;

class ChildItem extends Item
{
    /**
     * @var string
     */
    protected $type = 'child';
}
