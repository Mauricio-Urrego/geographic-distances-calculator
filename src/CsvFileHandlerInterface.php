<?php

namespace Mauriciourrego\Distance;

interface CsvFileHandlerInterface {
	public function readCsvFile(string $filePath): array;
	public function writeCsvFile(string $filePath, array $data): void;
}
