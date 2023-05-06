<?php

namespace Mauriciourrego\Distance;

interface GeoCodeInterface {
	public function getGeoCode(string $address): array;
}
