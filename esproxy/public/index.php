<?php

use App\Kernel;
use Lan\Ebs\Service\FileSystemCache;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\ErrorHandler\Debug;
use Symfony\Component\HttpFoundation\Request;

require_once dirname(__DIR__) . '/vendor/lan/ice-fork/source/bootstrap.php';

(new Dotenv())->bootEnv(dirname(__DIR__) . '/.env');

if ($_SERVER['APP_DEBUG']) {
    umask(0000);

    Debug::enable();
}

$cache = FileSystemCache::getAdapter(FileSystemCache::PROFILE);

$cache->prune();


global $kernel;

//$kernel = new Kernel($_SERVER['APP_ENV'], (bool)$_SERVER['APP_DEBUG']);
$kernel = new Kernel($_SERVER['APP_ENV'], true);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);