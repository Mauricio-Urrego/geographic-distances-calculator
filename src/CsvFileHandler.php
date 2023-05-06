<?php

namespace Mauriciourrego\Distance;

class CsvFileHandler implements CsvFileHandlerInterface {
	public function readCsvFile(string $filePath): array {
		$file = fopen($filePath, 'r');

		if (!$file) {
			throw new \RuntimeException("Failed to open file: $filePath");
		}

		$fileContents = [];
		while(!feof($file)) {
			$fileContents[] = fgetcsv($file, 0, '-');
		}
		fclose($file);
		return $fileContents;
	}

	public function writeCsvFile(string $filePath, array $data): void {
		$file = fopen($filePath, 'w');
		if (!$file) {
			throw new \RuntimeException("Failed to open file for writing: $filePath");
		}

		foreach($data as $row) {
			if (!fputcsv($file, $row)) {
				throw new \RuntimeException("Failed to write CSV data to file: $filePath");
			}
		}
		fclose($file);
	}
}
