<?php

use Ice\Core\Environment;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Ice Framework
require_once __DIR__.'/../vendor/lan/ice-fork/source/bootstrap.php';

define('EBS_DOMAIN', Environment::getInstance()->getConfig('Ebs\Security\Ebs')->getParams(['ebs'])[0]); // дергать Security нельзя);
define('EBS_UPLOAD_DIR', getUploadDir());

// Bootstrap Laravel and handle the request...
(require_once __DIR__.'/../bootstrap/app.php')
    ->handleRequest(Request::capture());
