# laravel-hyper-controller


### This project is not finished yet but if you want to use it remember to:
- whenever you create or modify a `HyperController` to delete the `bootstrap/cache/hyper_controllers.php` file
- you may run into autoloading problems if you don't configure the package correctly


## Publish configuration file
> php artisan vendor:publish --provider="Gianfriaur\HyperController\HyperControllerServiceProvider"

### A HyperController Example

```PHP

namespace App\Http\Controllers;

use Gianfriaur\HyperController\Attribute\Http\Controller as HC;
use Gianfriaur\HyperController\Enum\ActionMethodEnum;
use Illuminate\Http\Request;
use Gianfriaur\HyperController\Http\Controllers\HyperController;

#[HC\Controller(path: 'my/route', alias: 'my_route')]
class TestController extends HyperController
{

    // route: GET|HEAD /my/route
    // generate: route('my_route')
    #[HC\IndexAction()]
    public function list(){
        return 'index';
    }

    // route: GET|HEAD /my/route/fetch
    // generate: route('my_route.fetch')
    #[HC\Action( alias: 'retrieve')]
    public function fetch(){
        return 'fetch';
    }

    // route: GET|HEAD /my/route/test
    // generate: route('my_route.check')
    #[HC\Action(path:'test',alias: 'check')]
    public function check(){
        return 'check';
    }

    // route: GET|HEAD /my/route/detail/4
    // generate: route('my_route.detail',[ 'id' => 4 ])
    #[HC\Action(path:'detail/{id}',alias: 'detail')]
    public function detail(int $id)
    {
        return 'detail';
    }

    // route: GET|HEAD /my/route/detail/UUID/action
    // generate: route('my_route.detail_action',[ 'text' => 'UUID' ])
    #[HC\Action(path:'detail/{uuid}/action',alias: 'detail_action')]
    public function detailAction(string $uuid)
    {
        return 'detailAction';
    }

    // route: POST /my/route/detail/1
    // generate: route('my_route.create',[ 'text' => 1 ])
    #[HC\Action(method: [ActionMethodEnum::POST], path: 'detail/{id}', alias: 'create')]
    public function create(Request $request,int $id)
    {
      return 'create';
    }
    
    // route: PUT /my/route/detail/1
    // generate: route('my_route.update',[ 'text' => 1 ])
    #[HC\Action(method: [ActionMethodEnum::PUT], path: 'detail/{id}', alias: 'update')]
    public function create(Request $request,int $id)
    {
      return 'update';
    }
    
    // route: POST|GET /my/route/complex/1/2/my_text
    // generate: route('my_route.complex',[ 'id' => 1, 'id2' => 2, 'text' => 'my_text' ])
    #[HC\Action(method: [ActionMethodEnum::POST, ActionMethodEnum::GET], path: 'complex/{id}/{id2}/{text}')]
    public function complex(Request $request, int $id, int $id2, int $text)
    {
      return 'complex';
    }
}

```

#### Load Controller 
in the `AppServiceProvider` or any other `ServiceProvider` or in `routes/web.php`

add:

```PHP
Route::hyperController(App\Http\Controllers\TestController::class);
```

#### this controller will generate the following output in the `php artisan route:list` command
```
GET|POST|PUT|HEAD my/route/{action?} ............... my_route › App\Http\Controllers\TestController@handle
```

### list of all supported methods:

- `ActionMethodEnum:CONNECT`
- `ActionMethodEnum:DELETE`
- `ActionMethodEnum:GET`
- `ActionMethodEnum:HEAD`
- `ActionMethodEnum:OPTIONS`
- `ActionMethodEnum:POST`
- `ActionMethodEnum:PUT`
- `ActionMethodEnum:PATCH`

---

## Compatibility with gianfriaur/package-loader package
if the `gianfriaur/package-loader` package is present in the vendor it is possible to autoload the controllers

ex:

```PHP

use Gianfriaur\HyperController\PackageLoader\PackageWithHyperController;
use Gianfriaur\PackageLoader\PackageProvider\AbstractPackageProvider;

class TestPackageProvider extends AbstractPackageProvider implements PackageWithHyperController
{
    public function getHyperControllers(): array
    {
        return [
            ToUpdateAdmin::class,
        ];
    }
    
    ...
}
```

this feature can be turned off by setting the asdasd configuration to asdasd

`config/hyper_controller.php`
```PHP
return [

    ...
    
    /*
    |--------------------------------------------------------------------------
    | Package Loader Autoload
    |--------------------------------------------------------------------------
    |
    | if the 'gianfriaur/package-loader' package is installed it automatically
    |     loads the resolver for each packet
    |     can be:
    |         'auto' => if it is not found, the package does not perform any
    |                       action otherwise it behaves like true
    |         true   => if it doesn't find the package, throw an exception,
    |                       otherwise perform the action
    |         false  => no action will be taken even if the
    |                       'gianfriaur/package-loader' is installed
    |
    */
    'package_loader_autoload' => false,
    
    ...

];
```