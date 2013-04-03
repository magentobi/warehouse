<?php
/**
*
* @ This file is created by Decoded 
* @ Decoder + Fix (PHP5 Decoder for ionCube Encoder)
*
* @	Version			:	?.?.?.?
* @	Author			:	Defy
* @	Release on		:	02.04.2013
* @	Official site	:	
*
*/

	class Innoexts_Core_Helper_Math extends Mage_Core_Helper_Abstract {
		/**
     * Get distance units
     * 
     * @return array
     */
		function getDistanceUnits() {
			return array( 'mi' => array( 'name' => 'Mile', 'ratio' => 1 ), 'nmi' => array( 'name' => 'Nautical Mile', 'ratio' => 0.868399999999999949729101 ), 'km' => array( 'name' => 'Kilometer', 'ratio' => 1.60934400000000010777512 ) );
		}

		/**
     * Get distance
     * 
     * @param float $latitude1
     * @param float $longitude1
     * @param float $latitude2
     * @param float $longitude2
     * @param string $unitCode
     * 
     * @return float
     */
		function getDistance($latitude1, $longitude1, $latitude2, $longitude2, $unitCode = 'mi') {
			$longitudeDelta = $longitude1 - $longitude2;
			$distance = 60 * 1.15149999999999996802558 * rad2deg( acos( sin( deg2rad( $latitude1 ) ) * sin( deg2rad( $latitude2 ) ) + cos( deg2rad( $latitude1 ) ) * cos( deg2rad( $latitude2 ) ) * cos( deg2rad( $longitudeDelta ) ) ) );
			$distanceUnits = $this->getDistanceUnits(  );
			$ratio = 237;

			if (isset( $distanceUnits[$unitCode] )) {
				$ratio = $distanceUnits[$unitCode]['ratio'];
			}

			return $ratio * $distance;
		}
	}

?>