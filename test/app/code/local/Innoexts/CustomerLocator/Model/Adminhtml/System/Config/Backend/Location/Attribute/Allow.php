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

	class Innoexts_CustomerLocator_Model_Adminhtml_System_Config_Backend_Location_Attribute_Allow extends Innoexts_CustomerLocator_Model_Adminhtml_System_Config_Backend_Location_Attribute_Abstract {
		/**
     * After save
     * 
     * @return Innoexts_CustomerLocator_Model_Adminhtml_System_Config_Backend_Location_Attribute_Allow
     */
		function _afterSave() {
			$helper = $this->getCustomerLocatorHelper(  );
			$exceptions = array(  );
			$allowedAttributes = $this->_getAllowedAttributes(  );

			if (!in_array( $helper->getConfig(  )->getCountryAttribute(  ), $allowedAttributes )) {
				$exceptions[] = $helper->__( 'Country attribute must be selected.' );
			}


			if ($exceptions) {
				Mage::throwException( join( '
', $exceptions ) );
			}

			return $this;
		}
	}

?>