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

	class Innoexts_Warehouse_Model_Cataloginventory_Stock_Item_Api_V2 extends Innoexts_Warehouse_Model_Cataloginventory_Stock_Item_Api {
		/**
     * Update stock
     * 
     * @param int $productId
     * @param int $stockId
     * @param mixed $data
     * 
     * @return bool
     */
		function _update($productId, $data, $stockId) {
			$product = Mage::getModel( 'catalog/product' );

			if ($newId = $product->getIdBySku( $productId )) {
				$productId = $productId;
			}

			$storeId = $this->_getStoreId(  );
			$product->setStoreId( $storeId )->load( $productId );

			if (!$product->getId(  )) {
				$this->_fault( 'not_exists' );
			}

			$stocksData = $product->getStocksData(  );
			$stockData = (isset( $stocksData[$stockId] ) ? $stocksData[$stockId] : null);

			if (!$stockData) {
				$stockData = array(  );
			}

			$stockData['stock_id'] = $stockId;

			if (isset( $data->qty )) {
				$stockData['qty'] = $data->qty;
			}


			if (isset( $data->is_in_stock )) {
				$stockData['is_in_stock'] = $data->is_in_stock;
			}


			if (isset( $data->manage_stock )) {
				$stockData['manage_stock'] = $data->manage_stock;
			}


			if (isset( $data->use_config_manage_stock )) {
				$stockData['use_config_manage_stock'] = $data->use_config_manage_stock;
			}


			if ($this->getVersionHelper(  )->isGe1700(  )) {
				if (isset( $data->use_config_backorders )) {
					$stockData['use_config_backorders'] = $data->use_config_backorders;
				}


				if (isset( $data->backorders )) {
					$stockData['backorders'] = $data->backorders;
				}
			}

			$stocksData[$stockId] = $stockData;
			$product->setStocksData( $stocksData );
			$product->save(  );
			jmp;
			Mage_Core_Exception {
				$this->_fault( 'not_updated', $e->getMessage(  ) );
				return true;
			}
		}
	}

?>