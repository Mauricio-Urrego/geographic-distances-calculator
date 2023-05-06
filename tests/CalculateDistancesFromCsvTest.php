<?php

namespace Mauriciourrego\Distance;

use PHPUnit\Framework\TestCase;

class CalculateDistancesFromCsvTest extends TestCase {
	public function testCalculateDistancesFromCsv() {
		// Create mock objects for dependencies
		$csvFileHandler = $this->createMock(CsvFileHandlerInterface::class);
		$geoCode = $this->createMock(GeoCodeInterface::class);
		$distanceCalculator = $this->createMock(DistanceCalculatorInterface::class);

		// Set up expectations for mock objects
		$csvFileHandler->expects($this->once())
			->method('readCsvFile')
			->willReturn([
				['Name', 'Address'],
				['Headquarters', '123 Main St'],
				['Location 1', '456 Elm St'],
			]);

		$geoCode->expects($this->exactly(2))
			->method('getGeoCode')
			->willReturn(['latitude' => 123, 'longitude' => 456]);

		$distanceCalculator->expects($this->exactly(1))
			->method('calculateDistance')
			->willReturn(10.5);

		$csvFileHandler->expects($this->once())
			->method('writeCsvFile')
			->with('distances.csv', [
				[
					'Sortnumber' => 'Sortnumber',
                    'Distance' => 'Distance',
                    'Name' => 'Name',
                    'Address' => 'Address',
				],
				[
					'Sortnumber' => 1,
					'Distance' => '10.50 km',
					'Name' => 'Location 1',
					'Address' => '456 Elm St',
				]
			]);

		// Create an instance of CalculateDistancesFromCsv
		$calculateDistancesFromCsv = new CalculateDistancesFromCsv($csvFileHandler, $distanceCalculator, $geoCode);

		// Call the method to be tested
		$result = $calculateDistancesFromCsv->calculateDistancesFromCsv('addresses.csv', 'distances.csv');

		// Assert the result
		$this->assertTrue($result);
	}
}
