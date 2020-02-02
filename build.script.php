<?php
define("BASE_SRC", realpath(__DIR__."/app")."/");

$name = "Framer.phar";
$app = "console.php";

if(file_exists($name)) {
    unlink($name);
}

$phar = new Phar($name, 0, $name);
$phar->setSignatureAlgorithm(\Phar::SHA1);

// add everything under our APP dir
$it = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator(BASE_SRC, FilesystemIterator::SKIP_DOTS)
);

$phar->buildFromIterator($it, BASE_SRC);
$stub = '#!/usr/bin/env php'.PHP_EOL.$phar->createDefaultStub($app);
$phar->setStub($stub);