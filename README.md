# Laravel Dynamic Config

[![Latest Version on Packagist](https://img.shields.io/packagist/v/koomai/laravel-config.svg?style=flat-square)](https://packagist.org/packages/koomai/laravel-config)
[![Build Status](https://img.shields.io/travis/koomai/laravel-config/master.svg?style=flat-square)](https://travis-ci.org/koomai/laravel-config)
[![Quality Score](https://img.shields.io/scrutinizer/g/koomai/laravel-config.svg?style=flat-square)](https://scrutinizer-ci.com/g/koomai/laravel-config)
[![StyleCI](https://github.styleci.io/repos/185892143/shield?branch=master)](https://github.styleci.io/repos/185892143)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/koomai/laravel-config/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/koomai/laravel-config)

Manage your application configuration in your database.

Dynamic Config allows you to override or add to your application configuration in `/config` without redeploying code.

You can also use it as a separate custom configuration manager if you choose not to combine it with Laravel's config.

## Installation

You can install the package via composer. Select the appropriate version based on the table below.

```bash
composer require koomai/laravel-config
```

| Laravel  | This package |
| ------------- | ------------- |
| <=5.6  | ^1.0  |
| 5.7  | ^2.0  |
| 5.8  | ^3.0  |


## Usage

### Register Service Provider

The core service provider is already registered via package discovery.

If you want to combine/override Laravel's config values, you will have to manually register `CombinedConfigServiceProvider` in the `providers` array in `config/app.php`.

*Note*: It is highly recommended that you cache your config (using `artisan config:cache`) if you choose to do the above.

### Add config

```bash
// Simple key/value for mail configuration
php artisan config:add mail username johndoe

// Nested key/value for mail configuration
php artisan config:add mail from.address johndoe@example.com

// Refresh cache by passing the --reset-cache flag
php artisan config:add mail username johndoe --reset-cache
```

### Delete config

```bash
// Delete a key for mail configuration
php artisan config:delete mail username

// Delete a nested key for mail configuration
php artisan config:delete mail from.address

// Pass an empty string to delete all configuration for mail in the database
php artisan config:delete mail ''

// Refresh cache by passing the --reset-cache flag
php artisan config:delete mail username
```

### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.asdfas

## Credits

- [Sid K](https://github.com/koomai)
- [Spatie PHP Skeleton](https://github.com/spatie/skeleton-php)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
