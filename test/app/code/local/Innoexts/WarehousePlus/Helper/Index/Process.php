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

	class Innoexts_WarehousePlus_Helper_Index_Process extends Innoexts_Warehouse_Helper_Index_Process {
		/**
     * Get product flat process 
     * 
     * @return Mage_Index_Model_Process
     */
		function getProductFlat() {
			return Mage::getSingleton( 'index/indexer' )->getProcessByCode( 'catalog_product_flat' );
		}

		/**
     * Get search process 
     * 
     * @return Mage_Index_Model_Process
     */
		function getSearch() {
			return Mage::getSingleton( 'index/indexer' )->getProcessByCode( 'catalogsearch_fulltext' );
		}

		/**
     * Reindex product flat
     * 
     * @return Innoexts_WarehousePlus_Helper_Index_Process
     */
		function reindexProductFlat() {
			$process = $this->getProductFlat(  );

			if ($process) {
				$process->reindexAll(  );
			}

			return $this;
		}

		/**
     * Reindex search
     * 
     * @return Innoexts_WarehousePlus_Helper_Index_Process
     */
		function reindexSearchFlat() {
			$process = $this->getSearch(  );

			if ($process) {
				$process->reindexAll(  );
			}

			return $this;
		}

		/**
     * Change product flat process status
     * 
     * @param int $status
     * 
     * @return Innoexts_WarehousePlus_Helper_Index_Process
     */
		function changeProductFlatStatus($status) {
			$process = $this->getProductFlat(  );

			if ($process) {
				$process->changeStatus( $status );
			}

			return $this;
		}

		/**
     * Change search process status
     * 
     * @param int $status
     * 
     * @return Innoexts_WarehousePlus_Helper_Index_Process
     */
		function changeSearchStatus($status) {
			$process = $this->getSearchProcess(  );

			if ($process) {
				$process->changeStatus( $status );
			}

			return $this;
		}
	}

?>