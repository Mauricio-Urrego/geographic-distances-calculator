<?php

namespace Mauriciourrego\Distance;

use Dotenv;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

require_once 'vendor/autoload.php';

// Setting up .env file.
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$dotenv->required('POSITION_STACK_API_KEY');

// Setting up dependency injection.
$container = new ContainerBuilder();
$loader = new YamlFileLoader($container, new FileLocator(__DIR__));
try {
	$loader->load('services.yaml');
} catch (\Exception $e) {
	echo 'Failed loading services.yaml with exception: ' . $e->getMessage();
}
try {
	$distanceCalculatorFromCsv = $container->get(CalculateDistancesFromCsvInterface::class);
} catch (\Exception $e) {
	echo 'Failed getting container with exception: ' . $e->getMessage();
}

echo 'Working...';
$start = microtime(true); // To output script execution time.

$distanceCalculatorFromCsv->calculateDistancesFromCsv(
	'addresses.csv',
	'distances.csv'
);

$end = microtime(true);
echo PHP_EOL;
echo 'Execution time: ' . round($end - $start, 2) . ' seconds.';
echo PHP_EOL;
