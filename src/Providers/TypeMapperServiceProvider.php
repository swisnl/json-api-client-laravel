<?php

declare(strict_types=1);

namespace Swis\JsonApi\Client\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Swis\JsonApi\Client\Interfaces\TypeMapperInterface;

class TypeMapperServiceProvider extends BaseServiceProvider
{
    /**
     * A list of class names implementing \Swis\JsonApi\Client\Interfaces\ItemInterface.
     *
     * @var string[]
     */
    protected $items = [];

    public function boot(TypeMapperInterface $typeMapper)
    {
        foreach ($this->items as $class) {
            /** @var \Swis\JsonApi\Client\Interfaces\ItemInterface $item */
            $item = $this->app->make($class);

            $typeMapper->setMapping($item->getType(), $class);
        }
    }
}
