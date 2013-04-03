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

	require_once( 'Mage/Customer/controllers/AccountController.php' );
	class Innoexts_CustomerLocator_Customer_AccountController extends Mage_Customer_AccountController {
		/**
     * Get customer locator helper
     * 
     * @return Innoexts_CustomerLocator_Helper_Data
     */
		function getCustomerLocatorHelper() {
			return Mage::helper( 'customerlocator' );
		}

		/**
     * Action predispatch
     *
     * Check customer authentication for some actions
     */
		function preDispatch() {
			parent::preDispatch(  );
			$action = $this->getRequest(  )->getActionName(  );
			$pattern = '/^(applyAddress)/i';

			if (preg_match( $pattern, $action )) {
				$this->setFlag( '', 'no-dispatch', false );
				$this->_getSession(  )->setNoReferer( true );
			}

		}

		/**
     * Apply address action
     */
		function applyAddressAction() {
			$helper = $this->getCustomerLocatorHelper(  );
			$request = $this->getRequest(  );

			if ($request->isPost(  )) {
				$session = Mage::getSingleton( 'core/session' );
				$address = new Varien_Object(
				        array(
				                'country_id' => trim( $request->getPost( 'country_id' ) ),
				                'region_id' => trim( $request->getPost( 'region_id' ) ), 
				                'region' => trim( $request->getPost( 'region' ) ), 
				                'city' => trim( $request->getPost( 'city' ) ), 
				                'postcode' => trim( $request->getPost( 'postcode' ))
			                 ) );
				$helper->setCustomerAddress( $address );
				$session->addSuccess( $helper->__( 'Your location has been saved.' ) );
			}

			$this->_redirectReferer(  );
		}

		/**
     * Apply address id action
     */
		function applyAddressIdAction() {
			$helper = $this->getCustomerLocatorHelper(  );
			$request = $this->getRequest(  );

			if ($request->isPost(  )) {
				$session = Mage::getSingleton( 'core/session' );
				$addressId = trim( $request->getPost( 'address_id' ) );
				$helper->setCustomerAddressId( $addressId );
				$session->addSuccess( $helper->__( 'Your location has been saved.' ) );
			}

			$this->_redirectReferer(  );
		}
	}

?>