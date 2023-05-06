<?php

namespace Mauriciourrego\Distance;

use Dotenv;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

require_once 'vendor/autoload.php';

// Setting up .env file.
try {
	$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
	$dotenv->load();
	$dotenv->required(['POSITION_STACK_API_KEY', 'POSITION_STACK_API_URL']);
} catch(\Exception $e) {
	throw new \RuntimeException('Make sure to add a .env file to the root folder with POSITION_STACK_API_KEY and POSITION_STACK_API_URL variables');
}

// Setting up dependency injection.
$container = new ContainerBuilder();
$loader = new YamlFileLoader($container, new FileLocator(__DIR__));
try {
	$loader->load('services.yaml');
} catch (\Exception $e) {
	throw new \RuntimeException('Failed loading services.yaml');
}
try {
	$distanceCalculatorFromCsv = $container->get(CalculateDistancesFromCsvInterface::class);
} catch (\Exception $e) {
	throw new \RuntimeException('Failed getting container.');
}

echo 'Working...';
echo PHP_EOL;
$start = microtime(true); // To output script execution time.

$distanceCalculatorFromCsv->calculateDistancesFromCsv(
	'addresses.csv',
	'distances.csv'
);

$end = microtime(true);
echo PHP_EOL;
echo 'Success!';
echo PHP_EOL;
echo 'Execution time: ' . round($end - $start, 2) . ' seconds.';
echo PHP_EOL;
