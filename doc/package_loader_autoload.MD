## Laravel Hyper Controller with gianfriaur/package-loader

This library is part of the `gianfriaur/package-loader` compatible collection

Offers native integration via the `Gianfriaur\HyperController\PackageLoader\PackageWithHyperController` class

The integration is the same as for all the other packages,
the package must implement `PackageWithHyperController` and in the method `getHyperControllers` must return a collection of classes that extend `HyperController`

### Example

```PHP

use Gianfriaur\HyperController\PackageLoader\PackageWithHyperController;
use Gianfriaur\PackageLoader\PackageProvider\AbstractPackageProvider;
use Gianfriaur\TestPackages\TestHyperController\Http\Controllers\MyHyperController;

class TestHyperControllerPackageProvider extends AbstractPackageProvider implements PackageWithHyperController
{
    public function getHyperControllers(): array
    {
        return [
            MyHyperController::class,
        ];
    }
    
    // ...
}
```

### Config

this feature can be configured via the `package_loader_autoload` configuration parameter in `config/hyper_controller.php`, by default it is set to `auto`

```PHP
return [

    // ...
    
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
    
    // ...

];
```