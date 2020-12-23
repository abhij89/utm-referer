# Remember a visitor's original referer & utm tags

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
![Code Style Status](https://img.shields.io/github/workflow/status/spatie/laravel-referer/Check%20&%20fix%20styling?label=code%20style)

Remember a visitor's original referer, utm tags in cookies. 

## Installation

You can install the package via composer:

``` bash
composer require abhij89/utm-referer
```

The package will automatically register itself in Laravel 5.5. In Laravel 5.4. you'll manually need to register the `Abhij89\UTMReferer\UTMRefererServiceProvider` service provider in `config/app.php`.

You can publish the config file with:

```
php artisan vendor:publish --provider="Abhij89\UTMReferer\UTMRefererServiceProvider"
```

Publishing the config file is necessary if you want to change the keys in which the referer/utms are stored in the cookie or
if you want to disable a referer/utm source.

```php
return [

    /*
     * The key that will be used to remember the referer in the cookie.
     */
    'referer_cookie_key' => 'user-referer',
    
    /*
     * The key that will be used to remember the utm tags in the cookie.
     */
    'utm_cookie_key' => 'user-utms',

    /*
     * The sources used to determine the referer/utms.
     */
    'sources' => [
        Abhij89\UTMReferer\Sources\UTMSource::class,
        Abhij89\UTMReferer\Sources\RequestHeader::class,
    ],
];
```

## Usage

To capture the referer, all you need to do is add the `Abhij89\UTMReferer\CaptureReferer` middleware to your middleware stack. In most configuration's, you'll only want to capture the referer in "web" requests, so it makes sense to register it in the `web` stack. Make sure it comes **after** Laravel's `StartSession` middleware!

```php
// app/Http/Kernel.php

protected $middlewareGroups = [
    'web' => [
        // ...
        \Illuminate\Session\Middleware\StartSession::class,
        // ...
        \Abhij89\UTMReferer\CaptureReferer::class,
        // ...
    ],
    // ...
];
```

The easiest way to retrieve the referer is by just resolving it out of the container:

```php
use Abhij89\UTMReferer\UTMReferer;

$referer = app(UTMReferer::class)->get(); // 'google.com'
```

Or you could opt to use Laravel's automatic facades:

```php
use Facades\Abhij89\UTMReferer\UTMReferer;

$referer = UTMReferer::get(); // 'google.com'
```

An empty referer will never overwrite an exisiting referer. So if a visitor comes from google.com and visits a few pages on your site, those pages won't affect the referer since local hosts are ignored.

### Forgetting or manually setting the referer

The `Referer` class provides dedicated methods to forget, or manually set the referer.

```php
use Referer;

Referer::put('google.com');
Referer::get(); // 'google.com'
Referer::forget();
Referer::get(); // ''
```

### Changing the way the referer is determined

The referer is determined by doing checks on various sources, which are defined in the configuration.

```php
return [
    // ...
    'sources' => [
        Abhij89\Referer\Sources\UtmSource::class,
        Abhij89\Referer\Sources\RequestHeader::class,
    ],
];
```

A source implements the `Source` interface, and requires one method, `getReferer`. If a source is able to determine a referer, other sources will be ignored. In other words, the `sources` array is ordered by priority.

In the next example, we'll add a source that can use a `?ref` query parameter to determine the referer. Additionally, we'll ignore `?utm_source` parameters.

First, create the source implementations:

```php
namespace App\Referer;

use Illuminate\Http\Request;
use Abhij89\Referer\Source;

class RefParameter implements Source
{
    public function getReferer(Request $request): string
    {
        return $request->get('ref', ''); 
    }
}
```

Then register your source in the `sources` array. We'll also disable the `utm_source` while we're at it.

```php
return [
    // ...
    'sources' => [
        App\Referer\RefParameter::class,
        Abhij89\Referer\Sources\RequestHeader::class,
    ],
];
```

That's it! Source implementations can be this simple, or more advanced if necessary.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email freek@spatie.be instead of using the issue tracker.

## Credits

- [Sebastian De Deyne](https://github.com/sebastiandedeyne)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
