# Laravel Utilities

This is a package containing handy utils such as built-in authentication and authorization (Spatie Laravel Permissions),
easy model filtering (Filtertable) setup, and easy CRUD API setup (CrudController).

## Installation
Proceed to install the package via composer:

```bash
composer require cyaoz94/laravel-utilities:^1.0
```

## Usage
### CrudController
You can extend our controller in order to use our base functions to speed up your CRUD api development.
Just specify the model class in your constructor method and the CRUD methods defined in CrudController will know
which model to query for.
```php
// imports
use Cyaoz94\LaravelUtilities\CrudController;

class AdminUserController extends CrudController
{

    public function __construct(Request $request)
    {
        // let specify model class
        $this->modelClass = AdminUser::class;

        parent::__construct($request);
    }
    // your implementation here
}
```
### Filterable
You can also use our Filterable trait so that you can easily implement filtering logic for your
controller's CRUD methods.

First, use the trait in your model.
```php
// imports
use Cyaoz94\LaravelUtilities\Filters\Filterable;

class AdminUser extends Authenticatable 
{
    // use Filterable trait
    use HasFactory, Filterable;
}
```
Define your filter class. Here we will define a `name()` method to handle filtering for the name column.
Take note that the filter key should be always be in *snake_case*, eg. `some_key`. The `QueryFilter.php`
class will loop through all the parameters in the request body to see if any matching method names in *camelCase* are found.
Using back our `some_key` example, the matching function name will be `someKey()` 
```php
namespace Cyaoz94\LaravelUtilities\Filters;

class AdminUserFilter extends QueryFilter
{
    public function name($value)
    {
        parent::like('name', $value);
    }
}
```

Then in your controller's constructor, specify which filter class to use. Now, `name`'s value submitted in the request
body will be used for filtering.
```php
// imports
use Cyaoz94\LaravelUtilities\CrudController;
use Cyaoz94\LaravelUtilities\Filters\AdminUserFilter;

class AdminUserController extends CrudController
{

    public function __construct(Request $request)
    {
        $this->modelClass = AdminUser::class;
        // specify Filter class
        $this->filterClass = AdminUserFilter::class;

        parent::__construct($request);
    }

    // your implementation here
}
```
In certain scenarios, you will need to override the base functions of `CrudController`,
you can still utilize the Filterable trait by doing the following
```php
class AdminUserController extends CrudController
{
    // an overriding function in the child class
    public function index(Request $request)
    {
        $query = AdminUser::filter(new AdminUserFilter($request));

        // your implementations
    }
}
```
### Migrations
The migrations of this package are now publishable under the "migrations" tag. It will publish the `create_admin_users_table` migration in the migrations folder in the database path, prefixed with the current date and time via:

```bash
php artisan vendor:publish --provider="Cyaoz94\LaravelUtilities\LaravelUtilitiesServiceProvider" --tag="migrations"
```
Run the migrations to create `admin_users` table.
```bash
php artisan migrate
```
### Spatie Laravel Permission
You can easily integrate spatie laravel permission using our package. You should publish the `migration` and the `config/permission.php` config file with:
```bash
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
```
Run the migrations to create all related tables.
```bash
php artisan migrate
```
You need to register our middleware in `app/Http/Kernel.php` file. If you would like to override our middleware, you can create your new middleware and extend ours. Remember to register your middleware in `app/Http/Kernel.php`.
```php
protected $routeMiddleware = [
    'permission' => \Cyaoz94\LaravelUtilities\PermissionMiddleware::class,
];
```
You need to add PermissionMiddleware exception handling in `app/Exceptions/Handler.php`.
```php
namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Spatie\Permission\Exceptions\UnauthorizedException as SpatieUnauthorizedException;
use Throwable;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $exception, $message = '')
    {
        if ($exception instanceof SpatieUnauthorizedException) {
            return response()->json(
                [
                    'code' => 401,
                    'error_message' => 'Unauthorized',
                ], 401
            );
        }
    }
}
```
Use middleware in your routes.
```php
Route::get('', [AdminUserController::class, 'index'])->middleware('permission:admin-user.read');
```
### Seeders
The seeders of this package are now publishable under the "seeders" tag. It will publish the `RolePermissionSeeder` seeder in the seeders folder in the database path via:
```bash
php artisan vendor:publish --provider="Cyaoz94\LaravelUtilities\LaravelUtilitiesServiceProvider" --tag="seeders"
```
`RolePermissionSeeder` will seed basic permissions, create superadmin role, grant permissions to superadmin, and create one admin user as superadmin. Run the seeder via:
```bash
php artisan db:seed --class=RolePermissionSeeder
```
In `RolePermissionSeeder.php`, you can update `permissions` array according to your system needs. We included some basic one. You can always run this seeder when you added or updated your permissions. It helps to update accordingly.
```php
$permissions = [
    'admin-user.create',
    'admin-user.read',
    'admin-user.update',
    'admin-user.delete',
    'user.create',
    'user.read',
    'user.update',
    'user.delete',
    'role.create',
    'role.read',
    'role.update',
    'role.delete',
];
```
In `RolePermissionSeeder.php` and `Cyaoz94\LaravelUtilities\Models\AdminUser.php`, we defaulted `$guardName = 'admin'`. You might want to change it to other guard name, example `sanctum`.
You can create your own `AdminUser` model and extends our model. You need to update the guard name in both `RolePermissionsSeedeer.php` and `AdminUser` model.
```php
// database/seeders/RolePermissionSeeder.php

$guardName = 'sanctum';
```
```php
// app/Models/AdminUser.php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Cyaoz94\LaravelUtilities\Models\AdminUser as BaseAdminUser;

class AdminUser extends BaseAdminUser
{
    use HasApiTokens;

    protected $guard_name = 'sanctum';
}

```
### Role Features
To use role related features (example `RoleController`), please register RolePolicy in `AuthServiceProvider`.
```php
namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Spatie\Permission\Models\Role;
use Cyaoz94\LaravelUtilities\Policies\RolePolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Role::class => RolePolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}
```
### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please contact your senior dev.

## Credits

-   Casper Ho

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
