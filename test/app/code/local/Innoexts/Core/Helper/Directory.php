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

	class Innoexts_Core_Helper_Directory extends Mage_Directory_Helper_Data {
		private $_regionJson2 = null;

		/**
     * Get core helper
     * 
     * @return Innoexts_Core_Helper_Data
     */
		function getCoreHelper() {
			return Mage::helper( 'innoexts_core' );
		}

		/**
     * Get version helper
     * 
     * @return Innoexts_Core_Helper_Version
     */
		function getVersionHelper() {
			return $this->getCoreHelper(  )->getVersionHelper(  );
		}

		/**
     * Get regions data json
     *
     * @return string
     */
		function getRegionJson2() {
			if (!$this->_regionJson2) {
				$cacheKey = 'CORE_DIRECTORY_REGIONS_JSON2_STORE' . Mage::app(  )->getStore(  )->getId(  );

				if (Mage::app(  )->useCache( 'config' )) {
					$json = Mage::app(  )->loadCache( $cacheKey );
				}


				if (empty( $$json )) {
					$countryIds = array(  );
					foreach ($this->getCountryCollection(  ) as $country) {
						$countryIds[] = $country->getCountryId(  );
					}

					$collection = Mage::getModel( 'directory/region' )->getResourceCollection(  )->addCountryFilter( $countryIds )->load(  );
					$regions = array( 'config' => array( 'show_all_regions' => true, 'regions_required' => Mage::helper( 'core' )->jsonEncode( array(  ) ) ) );
					foreach ($collection as $region) {

						if (!$region->getRegionId(  )) {
							continue;
						}

						$regions[$region->getCountryId(  )][$region->getRegionId(  )] = array( 'code' => $region->getCode(  ), 'name' => $this->__( $region->getName(  ) ) );
					}

					$json = Mage::helper( 'core' )->jsonEncode( $regions );

					if (Mage::app(  )->useCache( 'config' )) {
						Mage::app(  )->saveCache( $json, $cacheKey, array( 'config' ) );
					}
				}

				$this->_regionJson2 = $json;
			}

			return $this->_regionJson2;
		}
	}

?>