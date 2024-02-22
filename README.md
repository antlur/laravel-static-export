# Export your laravel site as static files

[![Latest Version on Packagist](https://img.shields.io/packagist/v/antlur/laravel-static-export.svg?style=flat-square)](https://packagist.org/packages/antlur/laravel-static-export)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/antlur/laravel-static-export/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/antlur/laravel-static-export/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/antlur/laravel-static-export/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/antlur/laravel-static-export/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/antlur/laravel-static-export.svg?style=flat-square)](https://packagist.org/packages/antlur/laravel-static-export)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require antlur/laravel-static-export
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="static-export-config"
```

This is the contents of the published config file:

```php
return [
    'output_path' => base_path('dist'),

    'clear_before_export' => true,

    'kernal_namespace' => 'App\Http\Kernel',
];
```

## Usage

```bash
php artisan export
```

## Dynamic Data during static site generation

When generating the static site, you can use the ExportPaths attribute to define which routes should be generated. This is useful when you have dynamic data that you want to generate static pages for. For example, if you have a blog and you want to generate static pages for each blog post, you can use the ExportPaths attribute to define which routes should be generated. The rest of your logic can be handled as if it was a normal Laravel application.

```php
// web.php
Route::get('/blog/{slug}', [BlogController::class, 'show']);

// app/Http/Controllers/BlogController.php
use Antlur\Export\Attributes\ExportPaths;

class BlogController
{
    // You can pass a class that implements PathProvider
    #[ExportPaths(BlogPostPaths::class)]
    public function show(string $name)
    {}

    // Or you can pass an array of paths directly
    #[ExportPaths(['/blog/first-post', '/blog/second-post'])]
    public function show(string $name)
    {}
}

// app/PathProviders/BlogPostPaths.php
class BlogPostPaths implements \Antlur\Export\Contracts\PathProvider
{
    public function paths(): array
    {
        return [
            '/blog/first-post',
            '/blog/second-post',
        ];
    }
}
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

-   [Anthony Holmes](https://github.com/anthonyholmes)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
