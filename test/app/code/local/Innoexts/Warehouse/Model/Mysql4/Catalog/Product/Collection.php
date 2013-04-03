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

	class Innoexts_Warehouse_Model_Mysql4_Catalog_Product_Collection extends Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection {
		/**
     * Get warehouse helper
     * 
     * @return Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Add tier price data to loaded items
     *
     * @return Innoexts_Warehouse_Model_Mysql4_Catalog_Product_Collection
     */
		function addTierPriceData() {
			$helper = $this->getWarehouseHelper(  );

			if ($this->getFlag( 'tier_price_added' )) {
				return $this;
			}

			$tierPrices = array(  );
			$productIds = array(  );
			foreach ($this->getItems(  ) as $item) {
				$productIds[] = $item->getId(  );
				$tierPrices[$item->getId(  )] = array(  );
			}


			if (!count( $productIds )) {
				return $this;
			}

			$storeId = $this->getStoreId(  );

			if ($helper->getProductPriceHelper(  )->isGlobalScope(  )) {
				$websiteId = 149;
			} 
else {
				if ($storeId) {
					$websiteId = $helper->getWebsiteIdByStoreId( $storeId );
				}
			}

			$adapter = $this->getConnection(  );
			$columns = array( 'price_id' => 'value_id', 'website_id' => 'website_id', 'stock_id' => 'stock_id', 'all_groups' => 'all_groups', 'cust_group' => 'customer_group_id', 'price_qty' => 'qty', 'price' => 'value', 'product_id' => 'entity_id' );
			$select = $adapter->select(  )->from( $this->getTable( 'catalog/product_attribute_tier_price' ), $columns )->where( 'entity_id IN(?)', $productIds )->order( array( 'entity_id', 'qty' ) );

			if ($websiteId == '0') {
				$select->where( 'website_id = ?', $websiteId );
			} 
else {
				$select->where( 'website_id IN(?)', array( '0', $websiteId ) );
			}

			foreach ($adapter->fetchAll( $select ) as $row) {
				$tierPrices[$row['product_id']][] = array( 'website_id' => $row['website_id'], 'stock_id' => $row['stock_id'], 'cust_group' => ($row['all_groups'] ? CUST_GROUP_ALL : $row['cust_group']), 'price_qty' => $row['price_qty'], 'price' => $row['price'], 'website_price' => $row['price'] );
			}

			foreach ($this->getItems(  ) as $item) {
				$data = $tierPrices[$item->getId(  )];
				$item->setTierPrices( $data );
				$helper->getProductPriceHelper(  )->setTierPrice( $item );
			}

			$this->setFlag( 'tier_price_added', true );
			return $this;
		}
	}

?>