<?php  /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace Gianfriaur\HyperController\Attribute\Http\Controller;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
class WithoutMiddleware
{

    /**
     * array of alis or classname of Middleware
     * @param array<class-string>|string|array<class-string|string>  $middlewares
     */
    public function __construct(
        public array $middlewares,
    )
    {}

}
