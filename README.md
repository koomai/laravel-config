# Laravel Config

##### For Laravel 5.4 - 5.7

Manage your application configuration in your database.

Laravel Config allows you to override or add to your application configuration in `/config` without redeploying code.

You can also use it as a separate custom configuration manager if you choose not to combine it with Laravel's config.

## Installation

You can install the package via composer:

```bash
composer require koomai/laravel-config
```

## Usage

### Register Service Provider

If you want to use this package to override Laravel's config, you will have to manually register 
`CombinedConfigServiceProvider` in the `providers` array in `config/app.php`. 

### Add config

```bash
// Simple key/value for mail configuration
php artisan config:add mail username johndoe

// Nested key/value for mail configuration
php artisan config:add mail from.address johndoe@example.com
```

### Delete config

```bash
php artisan config:delete mail username

php artisan config:delete mail from.address

// Pass an empty string to delete all configuration for mail in the database
php artisan config:delete mail ''
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
