# NetInteractive User importer bundle (from CSV)

## Installation
Add Git repository `git@github.com:mgrajcarek/user-importer-poc.git` to your `composer.json` file:
```json
"repositories": [
    {
        "type": "vcs",
        "url": "git@github.com:mgrajcarek/user-importer-poc.git"
    }
],
```

Add `Dvs/CacheBundle` to your `composer.json` file:
```
$ composer require netinteractive/user-importer-bundle
```
Register the bundle in `app/AppKernel.php`:
```php
public function registerBundles()
{
    return array(
        // ...
        new NetInteractive\Bundle\UserImporterBundle\NetInteractiveUserImporterBundle(),
    );
}
```
## Testing
Setup the test suite using Composer:
```
$ composer install --dev
```

### PhpSpec
To run Specs (require dev dependencies)
```
$ vendor/bin/phpspec run
```
