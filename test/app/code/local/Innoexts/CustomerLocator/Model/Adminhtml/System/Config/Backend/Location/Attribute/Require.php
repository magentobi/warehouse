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

	class Innoexts_CustomerLocator_Model_Adminhtml_System_Config_Backend_Location_Attribute_Require extends Innoexts_CustomerLocator_Model_Adminhtml_System_Config_Backend_Location_Attribute_Abstract {
		/**
     * After save
     * 
     * @return Innoexts_CustomerLocator_Model_Adminhtml_System_Config_Backend_Location_Attribute_Require
     */
		function _afterSave() {
			$helper = $this->getCustomerLocatorHelper(  );
			$this->_getAllowedAttributes(  );
			$this->_getRequiredAttributes(  );
			$requiredAttributes = $allowedAttributes = $exceptions = array(  );
			foreach ($requiredAttributes as $attribute) {

				if (!in_array( $attribute, $allowedAttributes )) {
					$exceptions[] = $helper->__( 'Attribute "%s" is not available in allowed attributes.', $helper->getAttributeName( $attribute ) );
					continue;
				}
			}


			if ($exceptions) {
				Mage::throwException( join( '
', $exceptions ) );
			}

			return $this;
		}
	}

?>