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

	class Innoexts_Warehouse_Block_Adminhtml_Sales_Order_Shipment_View_Items extends Mage_Adminhtml_Block_Sales_Order_Shipment_View_Items {
		/**
     * Get warehouse helper
     *
     * @return Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Whether to show 'Return to stock' checkbox for item
     * @param Mage_Sales_Model_Order_Creditmemo_Item $item
     * 
     * @return bool
     */
		function canReturnItemToStock($item = null) {
			$canReturnToStock = Mage::getStoreConfig( XML_PATH_CAN_SUBTRACT );

			if (!is_null( $item )) {
				if (!$item->hasCanReturnToStock(  )) {
					$product = $item->getOrderItem(  )->getProduct(  );

					if (( $product->getId(  ) && $product->getStockItem(  )->getManageStock(  ) )) {
						$item->setCanReturnToStock( true );
					} 
else {
						$item->setCanReturnToStock( false );
					}
				}

				$canReturnToStock = $item->getCanReturnToStock(  );
			}

			return $canReturnToStock;
		}
	}

?>