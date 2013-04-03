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

	class Innoexts_Warehouse_Model_Session extends Mage_Core_Model_Session_Abstract {
		/**
     * Constructor
     */
		function __construct() {
			$namespace = 'warehouse';
			$this->init( $namespace );
			Mage::dispatchEvent( 'warehouse_session_init', array( 'geocoder_session' => $this ) );
		}

		/**
     * Set product stock ids
     * 
     * @param array $productStockIds
     * 
     * @return Innoexts_Warehouse_Model_Session
     */
		function setProductStockIds($productStockIds) {
			$this->setData( 'product_stock_ids', $productStockIds );
			return $this;
		}

		/**
     * Get product stock ids
     * 
     * @return array
     */
		function getProductStockIds() {
			$productStockIds = $this->getData( 'product_stock_ids' );

			if (!is_array( $productStockIds )) {
				$productStockIds = array(  );
			}

			return $productStockIds;
		}

		/**
     * Remove product stock ids
     * 
     * @return Innoexts_Warehouse_Model_Session
     */
		function removeProductStockIds() {
			$this->unsetData( 'product_stock_ids' );
			return $this;
		}

		/**
     * Set product stock id
     * 
     * @param int $productId
     * @param int $stockId
     * 
     * @return Innoexts_Warehouse_Model_Session
     */
		function setProductStockId($productId, $stockId) {
			$productStockIds = $this->getProductStockIds(  );
			$productStockIds[$productId] = $stockId;
			$this->setProductStockIds( $productStockIds );
			return $this;
		}

		/**
     * Get product stock id
     * 
     * @param int $productId
     * 
     * @return int
     */
		function getProductStockId($productId) {
			$productStockIds = $this->getProductStockIds(  );

			if (( isset( $productStockIds[$productId] ) && $productStockIds[$productId] )) {
				return (int)$productStockIds[$productId];
			}

		}

		/**
     * Set stock id
     * 
     * @param int $stockId
     * 
     * @return Innoexts_Warehouse_Model_Session
     */
		function setStockId($stockId) {
			$this->setData( 'stock_id', $stockId );
			return $this;
		}

		/**
     * Get stock id
     * 
     * @return int
     */
		function getStockId() {
			$stockId = $this->getData( 'stock_id' );
			return ($stockId ? (int)$stockId : null);
		}

		/**
     * Remove stock id
     * 
     * @return Innoexts_Warehouse_Model_Session
     */
		function removeStockId() {
			$this->unsetData( 'stock_id' );
			return $this;
		}
	}

?>