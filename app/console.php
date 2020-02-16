<?php
namespace Framer;

require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/config.php';

use Framer\Commands\CreateApp;
use Framer\Commands\CreateMigration;
use Framer\Commands\Migrate;
use Symfony\Component\Console\Application;

$app = new Application("Framer CLI", "v1.0.0");
$app->add(new CreateApp());
$app->add(new CreateMigration());
$app->add(new Migrate());
$app->run();
