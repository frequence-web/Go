<?php

require_once __DIR__ . '/../vendor/symfony/src/Symfony/Component/ClassLoader/UniversalClassLoader.php';

$loader = new \Symfony\Component\ClassLoader\UniversalClassLoader();
$loader->registerNamespaces(array(
     'Go'        => __DIR__,
     'Symfony'   => __DIR__.'/../vendor/symfony/src',
     'Gaufrette' => __DIR__.'/../vendor/Gaufrette/src',
     'OOSSH'     => __DIR__.'/../vendor/OOSSH/src',
));

$loader->register();
