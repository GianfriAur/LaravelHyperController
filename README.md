
<h1 align="center">Laravel Hyper Controller</h1>



## ⚽️ Goal

The library is primarily intended to reduce the time Laravel takes to solve a route.

For example let's imagine an application with 30 entities, each of them has the CRUD via api 
so we can assume that only for the model routes we have 150 routes, if we also have an administration 
section for managing these we can even get to 300 routes.

This package is found to be even more useful with systems that automatically create admin interfaces 
or api routes since they usually create a lot of these, such as Sonata in Symfony, which creates a 
mountain of routes by significantly increasing the request determination time.

This library allows you to **unify all the routes of a controller into one** avoiding that they grow exponentially slowing down your application

----
## ✨ Features


* Collects the routes of a controller into a single laravel route, [Read More](#)
* Total customization of a route with parameters [Read More](#)
* Middleware association for specific action, with insertion and/or removal rules [Read More](#)
* Highly performing and cached
* Free rewriting of all its parts and the ability to replace them via config [Read More](#)
* Configuring controllers via attribute (no extra files) [Read More](#)
* Compatibility with `gianfriaur/package-loader` package [Read More](#)

----
## 🤙🏼 Quickstart



#### 1) Install The package
> composer require gianfriaur/laravel-hyper-controller
#### 2) Publish configuration file
> php artisan vendor:publish --provider="Gianfriaur\HyperController\HyperControllerServiceProvider"

**3) Everything is ready, now you can start using HyperController [Create your first controller](./doc/create_your_first_controller.MD)**


you are curious and want to go deeper, read the [full documentation](./doc/index.MD)



----
## 📝 Next releases

- The `php artisan route:list -vv` command with the second level of verbosity will have to show all the information of a HyperController

---- 
## 🎉 License

The Laravel Hyper Controller package is licensed under the terms of the MIT license and is available for free.
