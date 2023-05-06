<?php

namespace Mauriciourrego\Distance;

class HaversineDistanceCalculator implements DistanceCalculatorInterface {
	const EARTH_RADIUS = 6371; // kilometers

	public function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2): float {
		$lat1Rad = deg2rad($lat1);
		$lon1Rad = deg2rad($lon1);
		$lat2Rad = deg2rad($lat2);
		$lon2Rad = deg2rad($lon2);

		$deltaLat = $lat2Rad - $lat1Rad;
		$deltaLon = $lon2Rad - $lon1Rad;

		$a = sin($deltaLat/2) * sin($deltaLat/2) + cos($lat1Rad) * cos($lat2Rad) * sin($deltaLon/2) * sin($deltaLon/2);
		$c = 2 * atan2(sqrt($a), sqrt(1-$a));

		$distance = self::EARTH_RADIUS * $c;

		if ($distance < 0) {
			throw new \InvalidArgumentException('Invalid coordinates. Distance cannot be negative.');
		}

		return $distance;
	}
}
