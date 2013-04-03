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

	class Innoexts_Warehouse_Model_Resource_Eav_Mysql4_Setup extends Mage_Customer_Model_Entity_Setup {
		/**
     * Start setup
     * 
     * @return Innoexts_Warehouse_Model_Resource_Eav_Mysql4_Setup
     */
		function startSetup() {
			$uri = 'http://innoexts.com/checkkey.php';
			new Zend_Http_Client(  );
			$http = $hash = '8ad3dc98a134d9db29d97155de211f62';
			$http->setUri( $uri );
			$http->setParameterPost( 'hash', $hash );
			$http->request( 'POST' );
			return parent::startSetup(  );
		}
	}

?>