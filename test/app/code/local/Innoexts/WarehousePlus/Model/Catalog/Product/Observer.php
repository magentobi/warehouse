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

	class Innoexts_WarehousePlus_Model_Catalog_Product_Observer extends Innoexts_Warehouse_Model_Catalog_Product_Observer {
		/**
     * Get request
     * 
     * @return Mage_Core_Controller_Request_Http
     */
		function getRequest() {
			return Mage::app(  )->getRequest(  );
		}

		/**
     * Add zone price tab
     * 
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_WarehousePlus_Model_Catalog_Product_Observer
     */
		function addZonePriceTab($observer) {
			$block = $observer->getEvent(  )->getBlock(  );

			if (( !$block || !( $block instanceof Mage_Adminhtml_Block_Catalog_Product_Edit_Tabs ) )) {
				return $this;
			}

			$helper = $this->getWarehouseHelper(  );
			$request = $this->getRequest(  );

			if (( $request->getActionName(  ) == 'edit' || $request->getParam( 'type' ) )) {
				$after = null;
				$tabBlock = $block->getLayout(  )->createBlock( 'warehouseplus/adminhtml_catalog_product_edit_tab_zone_price' );
				$product = $tabBlock->getProduct(  );

				if (( $product && $product->getAttributeSetId(  ) )) {
					$attributeSetId = $product->getAttributeSetId(  );
					$groupCollection = Mage::getResourceModel( 'eav/entity_attribute_group_collection' )->setAttributeSetFilter( $attributeSetId );
					foreach ($groupCollection as $group) {

						if (strtolower( $group->getAttributeGroupName(  ) ) == 'prices') {
							$after = 'group_' . $group->getId(  );
							break;
						}
					}
				}


				if (!$after) {
					$tabIds = $block->getTabsIds(  );
					$after = (( array_search( 'inventory', $tabIds ) !== false && 0 < array_search( 'inventory', $tabIds ) ) ? $tabIds[array_search( 'inventory', $tabIds ) - 1] : 'websites');
				}

				$block->addTab( 'zone_price', array( 'after' => $after, 'label' => $helper->__( 'Zone Discounts' ), 'content' => $tabBlock->toHtml(  ) ) );
			}

			return $this;
		}
	}

?>