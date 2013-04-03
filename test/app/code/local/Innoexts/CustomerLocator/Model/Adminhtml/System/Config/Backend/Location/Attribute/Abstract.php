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
	class Innoexts_CustomerLocator_Model_Adminhtml_System_Config_Backend_Location_Attribute_Abstract extends Mage_Core_Model_Config_Data {
		/**
     * Get customer locator helper
     * 
     * @return Innoexts_CustomerLocator_Helper_Data
     */
		function getCustomerLocatorHelper() {
			return Mage::helper( 'customerlocator' );
		}

		/**
     * Get allowed attributes
     * 
     * @return array
     */
		function _getAllowedAttributes() {
			if ($this->getData( 'groups/options/fields/allow_attributes/inherit' )) {
				return explode( ',', Mage::getConfig(  )->getNode( 'customerlocator/options/allow_attributes', $this->getScope(  ), $this->getScopeId(  ) ) );
			}

			return $this->getData( 'groups/options/fields/allow_attributes/value' );
		}

		/**
     * Get required attributes
     *
     * @return array
     */
		function _getRequiredAttributes() {
			if ($this->getData( 'groups/options/fields/require_attributes/inherit' )) {
				return explode( ',', Mage::getConfig(  )->getNode( 'customerlocator/options/require_attributes', $this->getScope(  ), $this->getScopeId(  ) ) );
			}

			return $this->getData( 'groups/options/fields/require_attributes/value' );
		}
	}
?>