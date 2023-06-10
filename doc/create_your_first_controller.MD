# Create Your First Hyper Controller

---

### first we create a class eg: `app/Http/Controllers/TestController.php`

This class will have to extends `HyperController`

```PHP
namespace App\Http\Controllers;

use Gianfriaur\HyperController\Http\Controllers\HyperController;

class TestController extends HyperController
{
    /* ... */
}
```

now we need to instruct laravel which route to mount this controller on

```PHP
use Gianfriaur\HyperController\Attribute\Http\Controller as HC;

#[HC\Controller(path: 'my/test/hc', alias: 'my_test_hc')]
class TestController extends HyperController
{
    /* ... */
}
```

this will cause all routes of this controller to start at the prefix `my/test/hc` and all aliases will start with `my_test_hc`

Now let's create the index and other routes

```PHP
use Gianfriaur\HyperController\Enum\ActionMethodEnum;

#[HC\Controller(path: 'my/test/hc', alias: 'my_test_hc')]
class TestController extends HyperController
{

    #[HC\IndexAction()]
    public function index(){ /* ... */ }

    #[HC\Action()]
    public function first_action(){ /* ... */ }

    #[HC\Action(path: 'good_name', alias: 'good_alis')]
    public function ugly_name(){ /* ... */ }

    #[HC\Action(method: [ActionMethodEnum::PUT],path: '')]
    public function put(){ /* ... */ }

    #[HC\Action(method: [ActionMethodEnum::PUT, ActionMethodEnum::POST])]
    public function multiple_methods(){ /* ... */ }

    #[HC\Action(path: 'action/{id}/{text}/{uuid}')]
    public function with_some_parameter(Request $request, int $id, string $text, string $uuid){ /* ... */ }
}
```

as a last action we have to register the controller, in `web.php` we add the following line
```PHP
Route::hyperController(App\Http\Controllers\TestController::class);
```

#### **At this point everything is ready!** 


## Infos

---

The route that is recorded is the following:
```
GET|POST|PUT|HEAD my/test/hc/{action?} ............ my_test_hc › TestController@handle
```  

in this case `{action?}` via regex can be:
```REGEX
 first_action|good_name|multiple_methods|action/\\b(?!/b)\\w+/\\b(?!/b)\\w+/\\b(?!/b)\\w+ 
 ```

you can also generate the routes via:

```PHP
route('my_test_hc');//                  => 'my/test/hc'
route('my_test_hc.first_action');//     => 'my/test/hc/first_action'
route('my_test_hc.good_alis');//        => 'my/test/hc/good_name'
route('my_test_hc.put');//              => 'my/test/hc'
route('my_test_hc.multiple_methods');// => 'my/test/hc/multiple_methods'
route(
    'my_test_hc.with_some_parameter',
    ['id' => 1, 'text' => 'my_text', 'uuid' => 'UUID' ]
);//                                    => 'my/test/hc/action/1/my_text/UUID'

```
