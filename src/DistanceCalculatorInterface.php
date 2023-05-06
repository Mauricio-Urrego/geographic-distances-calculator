<?php

namespace Mauriciourrego\Distance;

interface DistanceCalculatorInterface {
	public function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2): float;
}
