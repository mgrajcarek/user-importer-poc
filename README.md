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
$ composer require netinteractive/user-importer-bundle "~0.1"
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
Register bundle routes `app/config/routing.yml`
```php
net_interactive_user_importer:
    resource: "@NetInteractiveUserImporterBundle/Resources/config/routing.xml"
    prefix:   /
```

Then go to page `http(s)://your.domain/user/importer/upload` to begin.

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
