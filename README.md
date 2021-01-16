# Turbo Laravel Test Helpers

[![Latest Version on Packagist](https://img.shields.io/packagist/v/tonysm/turbo-laravel-test-helpers.svg?style=flat-square)](https://packagist.org/packages/tonysm/turbo-laravel-test-helpers)
[![Build Status](https://img.shields.io/travis/tonysm/turbo-laravel-test-helpers/master.svg?style=flat-square)](https://travis-ci.org/tonysm/turbo-laravel-test-helpers)
[![Quality Score](https://img.shields.io/scrutinizer/g/tonysm/turbo-laravel-test-helpers.svg?style=flat-square)](https://scrutinizer-ci.com/g/tonysm/turbo-laravel-test-helpers)
[![Total Downloads](https://img.shields.io/packagist/dt/tonysm/turbo-laravel-test-helpers.svg?style=flat-square)](https://packagist.org/packages/tonysm/turbo-laravel-test-helpers)

This package adds a couple macros and assertion helpers to your application using Turbo Laravel. This was built separately because it has different dependencies.

## Installation

You can install the package via composer:

```bash
composer require tonysm/turbo-laravel-test-helpers --dev
```

## Usage

Add the trait to your test case. This will make the `$this->turbo()` method available. This will add the correct header to your response, like so:

``` php
class ExampleTest extends TestCase
{
    use InteractsWithTurbo;
    
    /** @test */
    public function turbo_stream_test()
    {
        $response = $this->turbo()->post('my-route');
        
        $response->assertTurboStream();
        
        // Checks if one of the Turbo Stream responses matches this criteria.
        $response->assertHasTurboStream($target = 'users', $action = 'append');
        
        // Checks if there is no Turbo Stream tag for the criteria.
        $response->assertDoesntHaveTurboStream($target = 'empty_users', $action = 'remove');
    }
}
```

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email tonysm@hey.com instead of using the issue tracker.

## Credits

- [Tony Messias](https://github.com/tonysm)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).