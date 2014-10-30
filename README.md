# AsseticFilehashBuster #

Assetic Filehash Buster provides a WorkerInterface replacement for the standard
Assetic CacheBustingWorker which will generate an asset hash based on the sha1
hash of the accumulated file contents for a given asset.

The standard CacheBustingWorker that ships with Assetic builds its hash based on
the file location and timestamp of the assets. This allows cache busting to be
used in situations where file modification timestamp may change without content
changing.

Basic Usage
-------------


``` php
<?php

use Assetic\Factory\AssetFactory;
use CourseHero\AsseticFilehashBuster\FilehashCacheBustingWorker;

$factory = new AssetFactory('/path/to/asset/directory/');
$factory->setAssetManager($am);
$factory->setFilterManager($fm);
$factory->setDebug(true);
$factory->addWorker(new FilehashCacheBustingWorker());

$css = $factory->createAsset(array(
    '@reset',         // load the asset manager's "reset" asset
    'css/src/*.scss', // load every scss files from "/path/to/asset/directory/css/src/"
), array(
    'scss',           // filter through the filter manager's "scss" filter
    '?yui_css',       // don't use this filter in debug mode
));

echo $css->dump();
```

Usage in Symfony
-------------

to use the FileHashCacheBustingWorker in Symfony define it as a service with the
assetic.factory_worker tag

``` yaml
services:
    course_hero.assetic.worker.cache_busting:
        class: CourseHero\AsseticFilehashBuster\FilehashCacheBustingWorker
        public: false
        tags:
            - { name: assetic.factory_worker }
```
For performance reasons you may want to only declare this service in the
production environment (from within config_prod.yml)

Also be sure to turn off the standard CacheBustingWorker

``` yaml
assetic:
    workers:
        cache_busting: false
```