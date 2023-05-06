<?php

namespace Mauriciourrego\Distance;

class PositionStackGeoCode implements GeoCodeInterface {

	public function getGeoCode(string $address): array {
		$queryString = http_build_query(
			[
				'access_key' => $_ENV['POSITION_STACK_API_KEY'],
				'query' => $address,
				'output' => 'json',
				'limit' => 1,
			]
		);

		try {
			$ch = curl_init(sprintf('%s?%s', $_ENV['POSITION_STACK_API_URL'], $queryString));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			$json = curl_exec($ch);

			if ($json === false) {
				throw new \RuntimeException('Error executing cURL request: ' . curl_error($ch));
			}

			$statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			if ($statusCode !== 200) {
				throw new \RuntimeException('HTTP request failed with status code: ' . $statusCode);
			}

			curl_close($ch);

			$data = json_decode($json, true);
			if (json_last_error() !== JSON_ERROR_NONE) {
				throw new \RuntimeException('Failed to decode JSON response: ' . json_last_error_msg());
			}

			if (!isset($data['data'][0])) {
				throw new \RuntimeException('Invalid JSON response format');
			}

			return $data['data'][0];
		} catch (\Exception $e) {
			throw $e;
		}
	}
}
