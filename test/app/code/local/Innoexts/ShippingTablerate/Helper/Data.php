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

	class Innoexts_ShippingTablerate_Helper_Data extends Mage_Core_Helper_Abstract {
		private $_tablerates = null;

		/**
     * Get core helper
     * 
     * @return Innoexts_Core_Helper_Data
     */
		function getCoreHelper() {
			return Mage::helper( 'innoexts_core' );
		}

		/**
     * Get address helper
     * 
     * @return Innoexts_Core_Helper_Address
     */
		function getAddressHelper() {
			return $this->getCoreHelper(  )->getAddressHelper(  );
		}

		/**
     * Get table rates
     * 
     * @return array
     */
		function getTablerates() {
			if (is_null( $this->_tablerates )) {
				$this->_tablerates = array(  );
				$tablerateCollection = Mage::getResourceModel( 'shippingtablerate/tablerate_collection' );
				foreach ($tablerateCollection as $tablerate) {
					$this->_tablerates[$tablerate->getId(  )] = $tablerate;
				}
			}

			return $this->_tablerates;
		}

		/**
     * Retrieve table rate by id
     * 
     * @param int $tablerateId
     * 
     * @return Innoexts_ShippingTablerate_Model_Tablerate
     */
		function getTablerateById($tablerateId) {
			$tablerates = $this->getTablerates(  );

			if (isset( $tablerates[$tablerateId] )) {
				return $tablerates[$tablerateId];
			}

		}

		/**
     * Get websites
     *
     * @return array
     */
		function getWebsites() {
			return Mage::app(  )->getWebsites(  );
		}

		/**
     * Get default website
     * 
     * @return Mage_Core_Model_Website
     */
		function getDefaultWebsite() {
			$website = null;
			$websites = $this->getWebsites(  );

			if (count( $websites )) {
				$website = array_shift( $websites );
			}

			return $website;
		}

		/**
     * Get website
     * 
     * @return Mage_Core_Model_Website
     */
		function getWebsite() {
			$website = null;
			$websiteId = (int)Mage::app(  )->getFrontController(  )->getRequest(  )->getParam( 'website', 0 );

			if ($websiteId) {
				$website = Mage::app(  )->getWebsite( $websiteId );
			}


			if (!$website) {
				$website = $this->getDefaultWebsite(  );
			}

			return $website;
		}

		/**
     * Get website identifier
     * 
     * @param $website|null Mage_Core_Model_Website
     * @return mixed
     */
		function getWebsiteId($website = null) {
			if (is_null( $website )) {
				$website = $this->getWebsite(  );
			}

			return ($website ? $website->getId(  ) : null);
		}
	}

?>