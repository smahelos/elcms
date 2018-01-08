<?php

require __DIR__ . '/../vendor/autoload.php';

//$configurator = new Nette\Configurator;
//
////$configurator->setDebugMode(false); // enable for your remote IP
//$configurator->enableTracy(__DIR__ . '/../log');
//
//$configurator->setTimeZone('Europe/Prague');
//$configurator->setTempDirectory(__DIR__ . '/../temp');
//
//$configurator->createRobotLoader()
//	->addDirectory(__DIR__)
//	->register();
//
//$configurator->addConfig(__DIR__ . '/config/config.neon');
//$configurator->addConfig(__DIR__ . '/config/config.local.neon');
//
//$container = $configurator->createContainer();

//return $container;


/**
 * @param array $extraConfigs
 * @param array $params
 * @return \Nette\DI\Container
 */
$containerFactory = function (array $extraConfigs = [], array $params = []) {
    $configurator = new Nette\Configurator;
    //$configurator->setDebugMode('23.75.345.200'); // enable for your remote IP

    $configurator->enableDebugger(__DIR__ . '/../log');
    $configurator->setTimeZone('Europe/Prague');
    $configurator->setTempDirectory(__DIR__ . '/../temp');
    $configurator->addParameters($params);
    $configurator->createRobotLoader()
        ->addDirectory(__DIR__)
        ->register();

    foreach ($extraConfigs as $config) {
        $configurator->addConfig($config);
    }

    $configurator->addConfig(__DIR__ . '/config/config.neon');
    $configurator->addConfig(__DIR__ . '/config/config.local.neon');

    return $configurator->createContainer();
};

return $containerFactory;
