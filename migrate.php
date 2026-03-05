<?php
$config = require __DIR__ . '/config/database.php'; 

$pdo = new PDO(
    "mysql:host={$config['host']};charset={$config['charset']}",
    $config['username'],
    $config['password'],
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

$pdo->exec("CREATE DATABASE IF NOT EXISTS `{$config['dbname']}`");
$pdo->exec("USE `{$config['dbname']}`");

$files = glob('migrations/*.php');
sort($files);

foreach ($files as $file) {
    require_once $file;

    $baseName        = basename($file, '.php');
    $withoutPrefix   = preg_replace('/^\d+_/', '', $baseName);
    $className       = str_replace('_', '', ucwords($withoutPrefix, '_'));

    $migration = new $className();
    $migration->up($pdo);

    echo "{$baseName} tamamlandı\n";
}
