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

	class Innoexts_Core_Helper_Version extends Mage_Core_Helper_Abstract {
		/**
     * Get the current Magento version
     *
     * @return string
     */
		function getCurrent() {
			return Mage::getVersion(  );
		}

		/**
     * Compare versions
     * 
     * @param string $version1
     * @param string $version2
     * @param string $operator
     * 
     * @return int
     */
		function _compare($version1, $version2, $operator = null) {
			return version_compare( $version1, $version2, $operator );
		}

		/**
     * Compare version to the current
     * 
     * @param string $version
     * @param string $operator
     * 
     * @return int
     */
		function compare($version, $operator = null) {
			return $this->_compare( $this->getCurrent(  ), $version, $operator );
		}

		/**
     * Check if current version is greater or equal
     * 
     * @return bool
     */
		function isGe($version) {
			return $this->compare( $version, '>=' );
		}

		/**
     * Check if current version is less or equal
     * 
     * @return bool
     */
		function isLe($version) {
			return $this->compare( $version, '<=' );
		}

		/**
     * Check if current version is greater
     * 
     * @return bool
     */
		function isGt($version) {
			return $this->compare( $version, '>' );
		}

		/**
     * Check if current version is less
     * 
     * @return bool
     */
		function isLt($version) {
			return $this->compare( $version, '<' );
		}

		/**
     * Check if current version is equal
     * 
     * @return bool
     */
		function isEq($version) {
			return $this->compare( $version, '==' );
		}

		/**
     * Get enterprise edition minimal version
     * 
     * @return string
     */
		function getEEMinVersion() {
			return '1.8.0.0';
		}

		/**
     * Check if EE version is running
     * 
     * @return bool
     */
		function isEE() {
			return $this->isGe( $this->getEEMinVersion(  ) );
		}

		/**
     * Check if current version is equal or greater then 1.5.0.0 
     * 
     * @return bool
     */
		function isGe1500() {
			return ( ( $this->isGe( '1.5.0.0' ) && !$this->isEE(  ) ) || ( $this->isGe( '1.10.0.0' ) && $this->isEE(  ) ) );
		}

		/**
     * Check if current version is equal or greater then 1.5.1.0 
     * 
     * @return bool
     */
		function isGe1510() {
			return ( ( $this->isGe( '1.5.1.0' ) && !$this->isEE(  ) ) || ( $this->isGe( '1.10.1.0' ) && $this->isEE(  ) ) );
		}

		/**
     * Check if current version is equal or greater then 1.6.0.0 
     * 
     * @return bool
     */
		function isGe1600() {
			return ( ( $this->isGe( '1.6.0.0' ) && !$this->isEE(  ) ) || ( $this->isGe( '1.11.0.0' ) && $this->isEE(  ) ) );
		}

		/**
     * Check if current version is equal or greater then 1.6.1.0 
     * 
     * @return bool
     */
		function isGe1610() {
			return ( ( $this->isGe( '1.6.1.0' ) && !$this->isEE(  ) ) || ( $this->isGe( '1.11.1.0' ) && $this->isEE(  ) ) );
		}

		/**
     * Check if current version is equal or greater then 1.6.2.0 
     * 
     * @return bool
     */
		function isGe1620() {
			return ( ( $this->isGe( '1.6.2.0' ) && !$this->isEE(  ) ) || ( $this->isGe( '1.11.2.0' ) && $this->isEE(  ) ) );
		}

		/**
     * Check if current version is equal or greater then 1.7.0.0 
     * 
     * @return bool
     */
		function isGe1700() {
			return ( ( $this->isGe( '1.7.0.0' ) && !$this->isEE(  ) ) || ( $this->isGe( '1.12.0.0' ) && $this->isEE(  ) ) );
		}

		/**
     * Check if current version is equal or greater then 1.7.0.1 
     * 
     * @return bool
     */
		function isGe1701() {
			return ( ( $this->isGe( '1.7.0.1' ) && !$this->isEE(  ) ) || ( $this->isGe( '1.12.0.1' ) && $this->isEE(  ) ) );
		}

		/**
     * Check if current version is equal or greater then 1.7.0.2 
     * 
     * @return bool
     */
		function isGe1702() {
			return ( ( $this->isGe( '1.7.0.2' ) && !$this->isEE(  ) ) || ( $this->isGe( '1.12.0.2' ) && $this->isEE(  ) ) );
		}

		/**
     * Check if current version is equal or greater then 1.7.1.0 
     * 
     * @return bool
     */
		function isGe1710() {
			return ( ( $this->isGe( '1.7.1.0' ) && !$this->isEE(  ) ) || ( $this->isGe( '1.12.1.0' ) && $this->isEE(  ) ) );
		}
	}

?>