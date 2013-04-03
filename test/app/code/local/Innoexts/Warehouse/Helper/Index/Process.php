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

	class Innoexts_Warehouse_Helper_Index_Process extends Mage_Core_Helper_Abstract {
		/**
     * Get warehouse helper
     * 
     * @return Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Get product price process
     * 
     * @return Mage_Index_Model_Process
     */
		function getProductPrice() {
			return Mage::getSingleton( 'index/indexer' )->getProcessByCode( 'catalog_product_price' );
		}

		/**
     * Get stock process 
     * 
     * @return Mage_Index_Model_Process
     */
		function getStock() {
			return Mage::getSingleton( 'index/indexer' )->getProcessByCode( 'cataloginventory_stock' );
		}

		/**
     * Reindex product price
     * 
     * @return Innoexts_Warehouse_Helper_Index_Process
     */
		function reindexProductPrice() {
			$process = $this->getProductPrice(  );

			if ($process) {
				$process->reindexAll(  );
			}

			return $this;
		}

		/**
     * Reindex stock
     * 
     * @return Innoexts_Warehouse_Helper_Index_Process
     */
		function reindexStock() {
			$process = $this->getStock(  );

			if ($process) {
				$process->reindexAll(  );
			}

			return $this;
		}

		/**
     * Change product price process status
     * 
     * @param int $status
     * 
     * @return Innoexts_Warehouse_Helper_Index_Process
     */
		function changeProductPriceStatus($status) {
			$process = $this->getProductPrice(  );

			if ($process) {
				$process->changeStatus( $status );
			}

			return $this;
		}

		/**
     * Change stock process status
     * 
     * @param int $status
     * 
     * @return Innoexts_Warehouse_Helper_Index_Process
     */
		function changeStockStatus($status) {
			$process = $this->getProductPrice(  );

			if ($process) {
				$process->changeStatus( $status );
			}

			return $this;
		}
	}

?>