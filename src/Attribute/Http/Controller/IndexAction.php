<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
namespace Gianfriaur\HyperController\Attribute\Http\Controller;

use Gianfriaur\HyperController\Enum\ActionMethodEnum;

#[\Attribute(\Attribute::TARGET_METHOD)]
class IndexAction
{
    public function __construct(public array|ActionMethodEnum $method = ActionMethodEnum::GET)
    {
    }

}