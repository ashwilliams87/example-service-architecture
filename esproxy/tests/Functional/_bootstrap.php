<?php
try {
   require_once __DIR__ . '/../../vendor/lan/ice-fork/source/bootstrap.php';
} catch (\Error $e) {
    echo 'Ice bootstrap failed: ' . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n\n";
    exit;
}