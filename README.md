// TODO: write readme

Add to `bootstrap\app.php`
```php
if (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] === 'api.pm.e2e') {
    $app->loadEnvironmentFrom('.env.e2e');
}
```