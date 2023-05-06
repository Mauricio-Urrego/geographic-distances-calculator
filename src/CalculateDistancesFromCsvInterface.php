<?php

namespace Mauriciourrego\Distance;

interface CalculateDistancesFromCsvInterface {
	public function calculateDistancesFromCsv(string $inputFilePath, string $outputFilePath): bool;
}
