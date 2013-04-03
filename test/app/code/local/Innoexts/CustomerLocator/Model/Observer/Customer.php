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

	class Innoexts_CustomerLocator_Model_Observer_Customer {
		/**
     * Get customer locator helper
     *
     * @return Innoexts_CustomerLocator_Helper_Data
     */
		function getCustomerLocatorHelper() {
			return Mage::helper( 'customerlocator' );
		}

		/**
     * Customer after login handler
     * 
     * @param 	Varien_Event_Observer $observer
     * 
     * @return 	Innoexts_CustomerLocator_Model_Observer_Customer
     */
		function customerLoginAfter($observer) {
			$customer = $observer->getEvent(  )->getModel(  );

			if (( $customer && $customer instanceof Mage_Customer_Model_Customer )) {
				$this->getCustomerLocatorHelper(  )->unsetCustomerAddress(  );
			}

			return $this;
		}

		/**
     * Customer after logout handler
     * 
     * @param 	Varien_Event_Observer $observer
     * 
     * @return 	Innoexts_CustomerLocator_Model_Observer_Customer
     */
		function customerLogoutAfter($observer) {
			$customer = $observer->getEvent(  )->getCustomer(  );

			if (( $customer && $customer instanceof Mage_Customer_Model_Customer )) {
				$this->getCustomerLocatorHelper(  )->unsetCustomerAddress(  );
			}

			return $this;
		}
	}

?>