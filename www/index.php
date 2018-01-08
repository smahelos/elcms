<?php

/** @var callable $containerFactory */
$containerFactory = require __DIR__ . '/../app/bootstrap.php';
/** @var \Nette\DI\Container $container */
$container = $containerFactory();
$container->getService('application')->run();
