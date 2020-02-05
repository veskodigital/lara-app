# LaraApp for Laravel, a pocket friendly IOS & Android companion app.

[Official LaraApp package](https://thelara.app)
<br>
<br>
Link your smartphone to your Laravel project with LaraApp. 
Our package enables you to manage your project on the go, some features include:
* Notifications for users signed up
* View storage logs
* Routes
* Charts for users signed up
* Run artisan commands

<br>
[IOS App](https://apps.apple.com/us/app/laraapp-for-laravel-artisans/id1489590015) | 
[Android App](https://play.google.com/store/apps/details?id=com.mavsoft.LaraApp)

<br>
Download the IOS/Android app for free and link your Laravel project, follow the installtion below once you have the app.

## Installation

First, install the package via composer:

``` bash
composer require woosignal/laravel-laraapp
```

The package will automatically register itself.

## Configuration

Publish the LaraApp configuration:

```bash
php artisan laraapp:install
```

It will also ask if you want to run the migrations for LaraApp which adds 3 new tables to your project.
When the installation has finished, by default your login details for your user will as follows:
<br>`Email: me@lara.app`
<br>`Password: app123`

You can change the login details by running `php artisan laraapp:updateuser`

## Config
Once you run the php artisan laraapp:install command, it will copy the default config to `config/laraapp.php` where you can edit it.

## Authorization

If your Laravel site's enviroment is set to production, you'll need to add users who you want to be able to access the **/lara-app/link** route inside your LaraAppServiceProvider.php.
<br>
`app/Providers/LaraAppServiceProvider.php`
<br>
In this file you should see the following:
```php
protected function gate()
{
    Gate::define('viewLaraApp', function ($user) {
        return in_array($user->email, [
            'taylorotwell@laravel.com',
            // e.g. above...
        ]);
    });
}
```


**Next**

Open your **VerifyCsrfToken.php** file and add the **lara-app/\*** route to the except array.
`app/Http/Middleware/VerifyCsrfToken.php`

```php 
protected $except = [
        'lara-app/*'
        // ...
    ];
```

## Testing
Try and access https://mysite.com/lara-app/link
If you can access this then the setup is ready for the mobile app to connect too.
<br>
If you can't access the route (seeing 404/403 error), try running the following.
<br>
`php artisan config:clear && php artisan route:clear`
<br>
Or run `php artisan optimize`

## Documentation
You can view our full documentation here <br>[LaraApp Docs](https://thelara.app/docs/1.0/getting-started)

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Security

If you discover any security related issues, please email agordon@woosignal.com instead of using the issue tracker.

## Credits

- [Anthony Gordon](https://twitter.com/anthonygordn)
- [All Contributors](../../contributors)

## Support us

WooSignal is a development team based in the UK, we do mobile and web applications [on our website](https://woosignal.com).

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
