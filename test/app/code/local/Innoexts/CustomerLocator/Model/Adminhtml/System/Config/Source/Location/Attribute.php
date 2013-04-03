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

	class Innoexts_CustomerLocator_Model_Adminhtml_System_Config_Source_Location_Attribute {
		/**
     * Get customer locator helper
     * 
     * @return Innoexts_CustomerLocator_Helper_Data
     */
		function getCustomerLocatorHelper() {
			return Mage::helper( 'customerlocator' );
		}

		/**
     * Options getter
     * 
     * @return array
     */
		function toOptionArray() {
			return $this->getCustomerLocatorHelper(  )->getAttributesOptions(  );
		}
	}

?>