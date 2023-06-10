# Configuring HyperController via Attributes

The configuration of Laravel Hyper Controller is made with attributes.

The attributes are divided into 3 large families

* To configure the controller
* To configure an action
* For assigning middleware

---

## Configure HyperController

A HyperController is configured through an attribute called Controller, this admits the following parameters

---

### `Gianfriaur\HyperController\Attribute\Http\Controller\Controller`

As `Attribute::TARGET_CLASS`

| Name  | Type         | Default | Description                                                          |
|-------|--------------|---------|----------------------------------------------------------------------|
| path  | string       |         | represents the start of the routes of all actions of this controller |
| alias | string\|null | null    | represents the start of the alias of all actions of this controller  |

example:

```PHP
use Gianfriaur\HyperController\Attribute\Http\Controller as HC;

#[HC\Controller(path: 'my/test/hc', alias: 'my_test_hc')]
class TestController extends HyperController
{
    /* ... */
}
```

---

## Configure an Action

Routes can be indexed or no two annotations define these types of routes

---

### `Gianfriaur\HyperController\Attribute\Http\Controller\IndexAction`

As: `Attribute::TARGET_METHOD`

| Name   | Type                                      | Default               | Description                                    |
|--------|-------------------------------------------|-----------------------|------------------------------------------------|
| method | array<ActionMethodEnum>\|ActionMethodEnum | ActionMethodEnum::GET | represents the methods that accept this action |

[ActionMethodEnum List](./action_methods.MD)

Example:

```PHP
#[HC\Controller(/* ... */)]
class TestController extends HyperController
{

    #[HC\IndexAction()]
    public function index(){ /* ... */ }

}
```

### `Gianfriaur\HyperController\Attribute\Http\Controller\Action`

As: `Attribute::TARGET_METHOD`

| Name   | Type                                      | Default               | Description                                    |
|--------|-------------------------------------------|-----------------------|------------------------------------------------|
| method | array<ActionMethodEnum>\|ActionMethodEnum | ActionMethodEnum::GET | represents the methods that accept this action |
| path   | string\|null                              | name of method        | represents the following part of the routes    |
| alias  | string\|null                              | name of method        | represents the following part of the alias     |

[ActionMethodEnum List](./action_methods.MD)

Example:

```PHP
#[HC\Controller(/* ... */)]
class TestController extends HyperController
{

    #[HC\Action(path: 'good_name', alias: 'good_alis')]
    public function ugly_name(){ /* ... */ }

}
```

---

## Assigning Middleware

The controller's actions can be immediate through Middleware you can add or remove from what belongs to the controller

---

### `Gianfriaur\HyperController\Attribute\Http\Controller\Middleware`

As: `Attribute::TARGET_CLASS|Attribute::TARGET_METHOD`

| Name        | Type                                                     | Default | Description                               |
|-------------|----------------------------------------------------------|---------|-------------------------------------------|
| middlewares | array<class-string>\|string\|array<class-string\|string> | []      | add the following Middleware to the queue |

Example:

```PHP
#[HC\Controller(/* ... */)]
#[HC\Middleware(['web', MyWebMiddleware::class])]
class TestController extends HyperController
{

    #[HC\Action(/* ... */)]
    #[HC\Middleware([MyWebOtherMiddleware::class])]
    public function someAction(){ /* ... */ }

}
```

this causes the action to pass the following middlewares in order: 'web', MyWebMiddleware, MyWebOtherMiddleware

### `Gianfriaur\HyperController\Attribute\Http\Controller\WithoutMiddleware`

As: `Attribute::TARGET_METHOD`

| Name        | Type                                                     | Default | Description                                  |
|-------------|----------------------------------------------------------|---------|----------------------------------------------|
| middlewares | array<class-string>\|string\|array<class-string\|string> | []      | remove the following Middleware to the queue |

Example:

```PHP
#[HC\Controller(/* ... */)]
#[HC\Middleware(['web', MyMiddleware::class])]
class TestController extends HyperController
{

    #[HC\Action(/* ... */)]
    #[HC\Middleware(['api'])]
    #[HC\WithoutMiddleware(['web'])]
    public function someActionApi(){ /* ... */ }

}
```

this causes the action to pass the following middlewares in order: MyMiddleware, 'api'
