# Geographic Distances Calculator
## Set-up
1. `composer install`
2. Add a .env file with the PositionStack api url and api key.
3. `php main.php`

## How it works
There is an addresses.csv file in the root folder. The first address is your starting point. All subsequent addresses will be compared to the first.

## Extending this software
Simply add a new class for the google maps api or for different distance formulas (other than the current Haversine). The way it is set up with interfaces and dependency injection containers makes it easy to switch out these modules with minimal code editing. Especially useful when switching back and forth.

## Unit Tests
Can be found in the tests folder. Make sure to composer install with dev requirements.
Either:
1. `./vendor/phpunit/phpunit/phpunit tests/CalculateDistancesFromCsvTest.php`
2. `phpunit tests/CalculateDistancesFromCsvTest.php`
3. Or with your favorite IDE such as PHP Storm or VS Code.
