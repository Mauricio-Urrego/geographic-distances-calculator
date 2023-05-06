<?php

namespace Mauriciourrego\Distance;

class CalculateDistancesFromCsv implements CalculateDistancesFromCsvInterface {
	private CsvFileHandlerInterface $csvFileHandler;
	private DistanceCalculatorInterface $distanceCalculator;
	private GeoCodeInterface $geoCode;

	public function __construct(CsvFileHandlerInterface $csvFileHandler, DistanceCalculatorInterface $distanceCalculator, GeoCodeInterface $geoCode) {
		$this->csvFileHandler = $csvFileHandler;
		$this->distanceCalculator = $distanceCalculator;
		$this->geoCode = $geoCode;
	}

	public function calculateDistancesFromCsv(string $inputFilePath, string $outputFilePath): bool {
		$data = $this->csvFileHandler->readCsvFile($inputFilePath);
		if (trim($data[0][0]) === 'Name' || trim($data[0][1]) === 'Address') {
			array_shift($data); // Removes header.
		}

		if (count($data) < 2) {
			throw new \RuntimeException('Insufficient data. CSV must have at least two rows (excluding the header). First row is starting point, second is what to compare it to.');
		}

		$hqAddress = $data[0][1];
		$hqGeoCode = $this->geoCode->getGeoCode($hqAddress);
		$hqLatitude = $hqGeoCode['latitude'];
		$hqLongitude = $hqGeoCode['longitude'];

		$outputData = [];

		// Add header row.
		$outputData[] = [
			'Sortnumber' => 'Sortnumber',
			'Distance' => 'Distance',
			'Name' => 'Name',
			'Address' => 'Address',
		];

		foreach ($data as $rowIndex => $row) {
			if ($rowIndex < 1) continue; // Skip the first row as this is what we will be comparing to (the HQ).

			$address = $row[1];
			$geoData = $this->geoCode->getGeoCode($address);

			if (!$geoData || !isset($geoData['latitude']) || !isset($geoData['longitude'])) {
				throw new \RuntimeException("Failed to geocode address: $address");
			}

			$distance = $this->distanceCalculator->calculateDistance($hqLatitude, $hqLongitude, $geoData['latitude'], $geoData['longitude']);

			if ($distance < 0) {
				throw new \RuntimeException("Invalid distance calculated for address: $address");
			}

			$outputData[] = [
				'Sortnumber' => $rowIndex,
				'Distance' => number_format($distance, 2, '.', '') . " km",
				'Name' => trim($row[0]),
				'Address' => trim($address),
			];
		}

		$this->csvFileHandler->writeCsvFile($outputFilePath, $outputData);

		return true;
	}
}
