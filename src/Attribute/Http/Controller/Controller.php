<?php  /** @noinspection PhpMultipleClassDeclarationsInspection */
namespace Gianfriaur\HyperController\Attribute\Http\Controller;


#[\Attribute(\Attribute::TARGET_CLASS)]
class Controller
{
    public function __construct(
        public string $path,
        public string|null $alias = null,
    )
    {}
}