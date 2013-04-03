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

	class Innoexts_Core_Helper_Data extends Mage_Core_Helper_Abstract {
		/**
     * Get address helper
     * 
     * @return Innoexts_Core_Helper_Address
     */
		function getAddressHelper() {
			return Mage::helper( 'innoexts_core/address' );
		}

		/**
     * Get database helper
     * 
     * @return Innoexts_Core_Helper_Database
     */
		function getDatabaseHelper() {
			return Mage::helper( 'innoexts_core/database' );
		}

		/**
     * Get directory helper
     * 
     * @return Innoexts_Core_Helper_Directory
     */
		function getDirectoryHelper() {
			return Mage::helper( 'innoexts_core/directory' );
		}

		/**
     * Get math helper
     * 
     * @return Innoexts_Core_Helper_Math
     */
		function getMathHelper() {
			return Mage::helper( 'innoexts_core/math' );
		}

		/**
     * Get model helper
     * 
     * @return Innoexts_Core_Helper_Model
     */
		function getModelHelper() {
			return Mage::helper( 'innoexts_core/model' );
		}

		/**
     * Get version helper
     * 
     * @return Innoexts_Core_Helper_Version
     */
		function getVersionHelper() {
			return Mage::helper( 'innoexts_core/version' );
		}
	}

?>