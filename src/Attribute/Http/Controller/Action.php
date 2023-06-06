<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
namespace Gianfriaur\HyperController\Attribute\Http\Controller;

use Gianfriaur\HyperController\Enum\ActionMethodEnum;

#[\Attribute(\Attribute::TARGET_METHOD)]
class Action
{
    /**
     * @param array<ActionMethodEnum>|ActionMethodEnum $method
     */
    public function __construct(public array|ActionMethodEnum $method = ActionMethodEnum::GET ,public string|null $path = null,public string|null $alias = null)
    {
    }
}