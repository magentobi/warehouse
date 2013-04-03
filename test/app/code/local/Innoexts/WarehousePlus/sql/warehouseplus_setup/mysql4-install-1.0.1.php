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

	$installer = $productTable;
	$connection = $installer->getConnection(  );
	$storeTable = $installer->getTable( 'core/store' );
	$eavAttributeTable = $installer->getTable( 'eav/attribute' );
	$eavEntityTypeTable = $installer->getTable( 'eav/entity_type' );
	$productTable = $installer->getTable( 'catalog/product' );
	$productBatchPriceTable = $installer->getTable( 'catalog/product_batch_price' );
	$productBatchSpecialPriceTable = $installer->getTable( 'catalog/product_batch_special_price' );
	$productIndexBatchPriceTable = $installer->getTable( 'catalog/product_index_batch_price' );
	$productIndexBatchSpecialPriceTable = $installer->getTable( 'catalog/product_index_batch_special_price' );
	$productTierPriceTableName = 'catalog/product_attribute_tier_price';
	$productTierPriceTable = $installer->getTable( $productTierPriceTableName );
	$productIndexTierPriceTableName = 'catalog/product_index_tier_price';
	$productIndexTierPriceTable = $installer->getTable( $productIndexTierPriceTableName );
	$productZonePriceTable = $installer->getTable( 'catalog/product_zone_price' );
	$productIndexPriceTable = $installer->getTable( 'catalog/product_index_price' );
	$productIndexPriceIdxTable = $installer->getTable( 'catalog/product_price_indexer_idx' );
	$productIndexPriceTmpTable = $installer->getTable( 'catalog/product_price_indexer_tmp' );
	$productIndexPriceFinalIdxTable = $installer->getTable( 'catalog/product_price_indexer_final_idx' );
	$productIndexPriceFinalTmpTable = $installer->getTable( 'catalog/product_price_indexer_final_tmp' );
	$productIndexPriceBundleIdxTable = $installer->getTable( 'bundle/price_indexer_idx' );
	$productIndexPriceBundleTmpTable = $installer->getTable( 'bundle/price_indexer_tmp' );
	$productIndexPriceBundleSelectionIdxTable = $installer->getTable( 'bundle/selection_indexer_idx' );
	$productIndexPriceBundleSelectionTmpTable = $installer->getTable( 'bundle/selection_indexer_tmp' );
	$productIndexPriceBundleOptionIdxTable = $installer->getTable( 'bundle/option_indexer_idx' );
	$productIndexPriceBundleOptionTmpTable = $installer->getTable( 'bundle/option_indexer_tmp' );
	$productIndexPriceOptionAggregateIdxTable = $installer->getTable( 'catalog/product_price_indexer_option_aggregate_idx' );
	$productIndexPriceOptionAggregateTmpTable = $installer->getTable( 'catalog/product_price_indexer_option_aggregate_tmp' );
	$productIndexPriceOptionIdxTable = $installer->getTable( 'catalog/product_price_indexer_option_idx' );
	$productIndexPriceOptionTmpTable = $installer->getTable( 'catalog/product_price_indexer_option_tmp' );
	$productIndexPriceDownloadableIdxTable = $installer->getTable( 'downloadable/product_price_indexer_idx' );
	$productIndexPriceDownloadableTmpTable = $installer->getTable( 'downloadable/product_price_indexer_tmp' );
	$productIndexPriceCfgOptionAggregateIdxTable = $installer->getTable( 'catalog/product_price_indexer_cfg_option_aggregate_idx' );
	$productIndexPriceCfgOptionAggregateTmpTable = $installer->getTable( 'catalog/product_price_indexer_cfg_option_aggregate_tmp' );
	$productIndexPriceCfgOptionIdxTable = $installer->getTable( 'catalog/product_price_indexer_cfg_option_idx' );
	$productIndexPriceCfgOptionTmpTable = $installer->getTable( 'catalog/product_price_indexer_cfg_option_tmp' );
	$installer->startSetup(  );
	$connection->addColumn( $productBatchPriceTable, 'currency', 'varchar(3) not null after `stock_id`' );
	$connection->addColumn( $productBatchPriceTable, 'store_id', 'smallint(5) unsigned not null default 0 after `currency`' );
	$connection->addKey( $productBatchPriceTable, 'IDX_CATALOG_PRODUCT_BATCH_PRICE_CURRENCY', array( 'currency' ), 'index' );
	$connection->addKey( $productBatchPriceTable, 'FK_CATALOG_PRODUCT_BATCH_PRICE_STORE', array( 'store_id' ), 'index' );
	$connection->addConstraint( 'FK_CATALOG_PRODUCT_BATCH_PRICE_STORE', $productBatchPriceTable, 'store_id', $storeTable, 'store_id' );
	$connection->addKey( $productBatchPriceTable, 'PRIMARY', array( 'product_id', 'stock_id', 'currency', 'store_id' ), 'primary' );
	$connection->dropColumn( $productBatchPriceTable, 'website_id' );
	$connection->addColumn( $productBatchSpecialPriceTable, 'currency', 'varchar(3) not null after `stock_id`' );
	$connection->addColumn( $productBatchSpecialPriceTable, 'store_id', 'smallint(5) unsigned not null default 0 after `currency`' );
	$connection->addKey( $productBatchSpecialPriceTable, 'IDX_CATALOG_PRODUCT_BATCH_SPECIAL_PRICE_CURRENCY', array( 'currency' ), 'index' );
	$connection->addKey( $productBatchSpecialPriceTable, 'FK_CATALOG_PRODUCT_BATCH_SPECIAL_PRICE_STORE', array( 'store_id' ), 'index' );
	$connection->addConstraint( 'FK_CATALOG_PRODUCT_BATCH_SPECIAL_PRICE_STORE', $productBatchSpecialPriceTable, 'store_id', $storeTable, 'store_id' );
	$connection->addKey( $productBatchSpecialPriceTable, 'PRIMARY', array( 'product_id', 'stock_id', 'currency', 'store_id' ), 'primary' );
	$connection->dropColumn( $productBatchSpecialPriceTable, 'website_id' );
	$connection->addColumn( $productIndexBatchPriceTable, 'currency', 'varchar(3) not null after `stock_id`' );
	$connection->addColumn( $productIndexBatchPriceTable, 'store_id', 'smallint(5) unsigned not null default 0 after `currency`' );
	$connection->addKey( $productIndexBatchPriceTable, 'IDX_CATALOG_PRODUCT_INDEX_BATCH_PRICE_CURRENCY', array( 'currency' ), 'index' );
	$connection->addKey( $productIndexBatchPriceTable, 'FK_CATALOG_PRODUCT_INDEX_BATCH_PRICE_STORE', array( 'store_id' ), 'index' );
	$connection->addConstraint( 'FK_CATALOG_PRODUCT_INDEX_BATCH_PRICE_STORE', $productIndexBatchPriceTable, 'store_id', $storeTable, 'store_id' );
	$connection->addKey( $productIndexBatchPriceTable, 'PRIMARY', array( 'entity_id', 'stock_id', 'currency', 'store_id' ), 'primary' );
	$connection->dropColumn( $productIndexBatchPriceTable, 'website_id' );
	$connection->addColumn( $productIndexBatchSpecialPriceTable, 'currency', 'varchar(3) not null after `stock_id`' );
	$connection->addColumn( $productIndexBatchSpecialPriceTable, 'store_id', 'smallint(5) unsigned not null default 0 after `currency`' );
	$connection->addKey( $productIndexBatchSpecialPriceTable, 'IDX_CATALOG_PRODUCT_INDEX_BATCH_SPECIAL_PRICE_CURRENCY', array( 'currency' ), 'index' );
	$connection->addKey( $productIndexBatchSpecialPriceTable, 'FK_CATALOG_PRODUCT_INDEX_BATCH_SPECIAL_PRICE_STORE', array( 'store_id' ), 'index' );
	$connection->addConstraint( 'FK_CATALOG_PRODUCT_INDEX_BATCH_SPECIAL_PRICE_STORE', $productIndexBatchSpecialPriceTable, 'store_id', $storeTable, 'store_id' );
	$connection->addKey( $productIndexBatchSpecialPriceTable, 'PRIMARY', array( 'entity_id', 'stock_id', 'currency', 'store_id' ), 'primary' );
	$connection->dropColumn( $productIndexBatchSpecialPriceTable, 'website_id' );
	$connection->addColumn( $productTierPriceTable, 'currency', 'varchar(3) null default null after `stock_id`' );
	$connection->addKey( $productTierPriceTable, 'IDX_CATALOG_PRODUCT_ENTITY_TIER_PRICE_CURRENCY', array( 'currency' ), 'index' );
	$connection->addColumn( $productTierPriceTable, 'store_id', 'smallint(5) unsigned not null default 0 after `currency`' );
	$connection->addKey( $productTierPriceTable, 'FK_CATALOG_PRODUCT_ENTITY_TIER_PRICE_STORE', array( 'store_id' ), 'index' );
	$connection->addConstraint( 'FK_CATALOG_PRODUCT_ENTITY_TIER_PRICE_STORE', $productTierPriceTable, 'store_id', $storeTable, 'store_id' );

	if (Mage::helper( 'warehouse' )->getVersionHelper(  )->isGe1600(  )) {
		$productTierPriceIndexes = $connection->getIndexList( $productTierPriceTable );
		foreach ($productTierPriceIndexes as $index) {

			if ($index['INDEX_TYPE'] == INDEX_TYPE_UNIQUE) {
				$connection->dropIndex( $productTierPriceTable, $index['KEY_NAME'] );
				continue;
			}
		}

		$connection->addIndex( $productTierPriceTable, $installer->getIdxName( $productTierPriceTableName, array( 'entity_id', 'all_groups', 'customer_group_id', 'qty', 'website_id', 'stock_id', 'currency', 'store_id' ), INDEX_TYPE_UNIQUE ), array( 'entity_id', 'all_groups', 'customer_group_id', 'qty', 'website_id', 'stock_id', 'currency', 'store_id' ), INDEX_TYPE_UNIQUE );
	} 
else {
		$connection->addKey( $productTierPriceTable, 'UNQ_CATALOG_PRODUCT_TIER_PRICE', array( 'entity_id', 'all_groups', 'customer_group_id', 'qty', 'website_id', 'stock_id', 'currency', 'store_id' ), 'unique' );
	}

	$connection->addColumn( $productIndexTierPriceTable, 'currency', 'varchar(3) null default null after `stock_id`' );
	$connection->addKey( $productIndexTierPriceTable, 'IDX_CATALOG_PRODUCT_INDEX_TIER_PRICE_CURRENCY', array( 'currency' ), 'index' );
	$connection->addColumn( $productIndexTierPriceTable, 'store_id', 'smallint(5) unsigned not null default 0 after `currency`' );
	$connection->addKey( $productIndexTierPriceTable, 'FK_CATALOG_PRODUCT_INDEX_TIER_PRICE_STORE', array( 'store_id' ), 'index' );
	$connection->addConstraint( 'FK_CATALOG_PRODUCT_INDEX_TIER_PRICE_STORE', $productIndexTierPriceTable, 'store_id', $storeTable, 'store_id' );
	$connection->addKey( $productIndexTierPriceTable, 'PRIMARY', array( 'entity_id', 'customer_group_id', 'website_id', 'stock_id', 'currency', 'store_id' ), 'primary' );
	$installer->run( '
CREATE TABLE `' . $productZonePriceTable . '` (
    `zone_price_id` int(10) unsigned not null auto_increment, 
    `product_id` int(10) unsigned not null, 
    `country_id` varchar(4) not null default \'0\', 
    `region_id` int(10) not null default \'0\', 
    `zip` varchar(21) null default null, 
    `is_zip_range` tinyint(1) unsigned not null default 0, 
    `from_zip` int(10) unsigned null default null, 
    `to_zip` int(10) unsigned null default null, 
    `price` decimal(12,4) NOT NULL default \'0.00\', 
    `price_type` enum(\'fixed\', \'percent\') NOT NULL default \'fixed\', 
    PRIMARY KEY  (`zone_price_id`), 
    KEY `IDX_CATALOG_PRODUCT_ZONE_PRICE_COUNTRY` (`country_id`), 
    KEY `IDX_CATALOG_PRODUCT_ZONE_PRICE_REGION` (`region_id`), 
    KEY `IDX_CATALOG_PRODUCT_ZONE_PRICE_ZIP` (`zip`), 
    KEY `IDX_CATALOG_PRODUCT_ZONE_PRICE_FROM_ZIP` (`from_zip`), 
    KEY `IDX_CATALOG_PRODUCT_ZONE_PRICE_TO_ZIP` (`to_zip`), 
    KEY `FK_CATALOG_PRODUCT_ZONE_PRICE_PRODUCT` (`product_id`), 
    CONSTRAINT `FK_CATALOG_PRODUCT_ZONE_PRICE_PRODUCT` FOREIGN KEY (`product_id`) 
        REFERENCES ' . $productTable . ' (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
' );
	$installer->run( 'UPDATE `' . $eavAttributeTable . '` 
SET `backend_model` = \'catalog/product_attribute_backend_finishdate\' 
WHERE (`attribute_code` = \'special_to_date\') AND (`entity_type_id` = (
    SELECT `entity_type_id` FROM `' . $eavEntityTypeTable . '` WHERE `entity_type_code` = \'catalog_product\'
))' );
	$connection->addColumn( $productIndexPriceTable, 'currency', 'varchar(3) null default null' );
	$connection->addKey( $productIndexPriceTable, 'IDX_CATALOG_PRODUCT_INDEX_PRICE_CURRENCY', array( 'currency' ), 'index' );
	$connection->addColumn( $productIndexPriceTable, 'store_id', 'smallint(5) unsigned not null default 0' );
	$connection->addKey( $productIndexPriceTable, 'FK_CATALOG_PRODUCT_INDEX_PRICE_STORE', array( 'store_id' ), 'index' );
	$connection->addConstraint( 'FK_CATALOG_PRODUCT_INDEX_PRICE_STORE', $productIndexPriceTable, 'store_id', $storeTable, 'store_id' );
	$connection->addKey( $productIndexPriceTable, 'PRIMARY', array( 'entity_id', 'customer_group_id', 'website_id', 'stock_id', 'currency', 'store_id' ), 'primary' );
	$connection->addColumn( $productIndexPriceIdxTable, 'currency', 'varchar(3) null default null' );
	$connection->addKey( $productIndexPriceIdxTable, 'IDX_CATALOG_PRODUCT_INDEX_PRICE_IDX_CURRENCY', array( 'currency' ), 'index' );
	$connection->addColumn( $productIndexPriceIdxTable, 'store_id', 'smallint(5) unsigned not null default 0' );
	$connection->addKey( $productIndexPriceIdxTable, 'FK_CATALOG_PRODUCT_INDEX_PRICE_IDX_STORE', array( 'store_id' ), 'index' );
	$connection->addConstraint( 'FK_CATALOG_PRODUCT_INDEX_PRICE_IDX_STORE', $productIndexPriceIdxTable, 'store_id', $storeTable, 'store_id' );
	$connection->addKey( $productIndexPriceIdxTable, 'PRIMARY', array( 'entity_id', 'customer_group_id', 'website_id', 'stock_id', 'currency', 'store_id' ), 'primary' );
	$connection->addColumn( $productIndexPriceTmpTable, 'currency', 'varchar(3) null default null' );
	$connection->addKey( $productIndexPriceTmpTable, 'IDX_CATALOG_PRODUCT_INDEX_PRICE_TMP_CURRENCY', array( 'currency' ), 'index' );
	$connection->addColumn( $productIndexPriceTmpTable, 'store_id', 'smallint(5) unsigned not null default 0' );
	$connection->addKey( $productIndexPriceTmpTable, 'FK_CATALOG_PRODUCT_INDEX_PRICE_TMP_STORE', array( 'store_id' ), 'index' );
	$connection->addConstraint( 'FK_CATALOG_PRODUCT_INDEX_PRICE_TMP_STORE', $productIndexPriceTmpTable, 'store_id', $storeTable, 'store_id' );
	$connection->addKey( $productIndexPriceTmpTable, 'PRIMARY', array( 'entity_id', 'customer_group_id', 'website_id', 'stock_id', 'currency', 'store_id' ), 'primary' );
	$connection->addColumn( $productIndexPriceFinalIdxTable, 'currency', 'varchar(3) null default null' );
	$connection->addColumn( $productIndexPriceFinalIdxTable, 'store_id', 'smallint(5) unsigned not null default 0' );
	$connection->addKey( $productIndexPriceFinalIdxTable, 'PRIMARY', array( 'entity_id', 'customer_group_id', 'website_id', 'stock_id', 'currency', 'store_id' ), 'primary' );
	$connection->addColumn( $productIndexPriceFinalTmpTable, 'currency', 'varchar(3) null default null' );
	$connection->addColumn( $productIndexPriceFinalTmpTable, 'store_id', 'smallint(5) unsigned not null default 0' );
	$connection->addKey( $productIndexPriceFinalTmpTable, 'PRIMARY', array( 'entity_id', 'customer_group_id', 'website_id', 'stock_id', 'currency', 'store_id' ), 'primary' );
	$connection->addColumn( $productIndexPriceBundleIdxTable, 'currency', 'varchar(3) null default null' );
	$connection->addColumn( $productIndexPriceBundleIdxTable, 'store_id', 'smallint(5) unsigned not null default 0' );
	$connection->addKey( $productIndexPriceBundleIdxTable, 'PRIMARY', array( 'entity_id', 'customer_group_id', 'website_id', 'stock_id', 'currency', 'store_id' ), 'primary' );
	$connection->addColumn( $productIndexPriceBundleTmpTable, 'currency', 'varchar(3) null default null' );
	$connection->addColumn( $productIndexPriceBundleTmpTable, 'store_id', 'smallint(5) unsigned not null default 0' );
	$connection->addKey( $productIndexPriceBundleTmpTable, 'PRIMARY', array( 'entity_id', 'customer_group_id', 'website_id', 'stock_id', 'currency', 'store_id' ), 'primary' );
	$connection->addColumn( $productIndexPriceBundleSelectionIdxTable, 'currency', 'varchar(3) null default null' );
	$connection->addColumn( $productIndexPriceBundleSelectionIdxTable, 'store_id', 'smallint(5) unsigned not null default 0' );
	$connection->addKey( $productIndexPriceBundleSelectionIdxTable, 'PRIMARY', array( 'entity_id', 'customer_group_id', 'website_id', 'option_id', 'selection_id', 'stock_id', 'currency', 'store_id' ), 'primary' );
	$connection->addColumn( $productIndexPriceBundleSelectionTmpTable, 'currency', 'varchar(3) null default null' );
	$connection->addColumn( $productIndexPriceBundleSelectionTmpTable, 'store_id', 'smallint(5) unsigned not null default 0' );
	$connection->addKey( $productIndexPriceBundleSelectionTmpTable, 'PRIMARY', array( 'entity_id', 'customer_group_id', 'website_id', 'option_id', 'selection_id', 'stock_id', 'currency', 'store_id' ), 'primary' );
	$connection->addColumn( $productIndexPriceBundleOptionIdxTable, 'currency', 'varchar(3) null default null' );
	$connection->addColumn( $productIndexPriceBundleOptionIdxTable, 'store_id', 'smallint(5) unsigned not null default 0' );
	$connection->addKey( $productIndexPriceBundleOptionIdxTable, 'PRIMARY', array( 'entity_id', 'customer_group_id', 'website_id', 'option_id', 'stock_id', 'currency', 'store_id' ), 'primary' );
	$connection->addColumn( $productIndexPriceBundleOptionTmpTable, 'currency', 'varchar(3) null default null' );
	$connection->addColumn( $productIndexPriceBundleOptionTmpTable, 'store_id', 'smallint(5) unsigned not null default 0' );
	$connection->addKey( $productIndexPriceBundleOptionTmpTable, 'PRIMARY', array( 'entity_id', 'customer_group_id', 'website_id', 'option_id', 'stock_id', 'currency', 'store_id' ), 'primary' );
	$connection->addColumn( $productIndexPriceOptionAggregateIdxTable, 'currency', 'varchar(3) null default null' );
	$connection->addColumn( $productIndexPriceOptionAggregateIdxTable, 'store_id', 'smallint(5) unsigned not null default 0' );
	$connection->addKey( $productIndexPriceOptionAggregateIdxTable, 'PRIMARY', array( 'entity_id', 'customer_group_id', 'website_id', 'option_id', 'stock_id', 'currency', 'store_id' ), 'primary' );
	$connection->addColumn( $productIndexPriceOptionAggregateTmpTable, 'currency', 'varchar(3) null default null' );
	$connection->addColumn( $productIndexPriceOptionAggregateTmpTable, 'store_id', 'smallint(5) unsigned not null default 0' );
	$connection->addKey( $productIndexPriceOptionAggregateTmpTable, 'PRIMARY', array( 'entity_id', 'customer_group_id', 'website_id', 'option_id', 'stock_id', 'currency', 'store_id' ), 'primary' );
	$connection->addColumn( $productIndexPriceOptionIdxTable, 'currency', 'varchar(3) null default null' );
	$connection->addColumn( $productIndexPriceOptionIdxTable, 'store_id', 'smallint(5) unsigned not null default 0' );
	$connection->addKey( $productIndexPriceOptionIdxTable, 'PRIMARY', array( 'entity_id', 'customer_group_id', 'website_id', 'stock_id', 'currency', 'store_id' ), 'primary' );
	$connection->addColumn( $productIndexPriceOptionTmpTable, 'currency', 'varchar(3) null default null' );
	$connection->addColumn( $productIndexPriceOptionTmpTable, 'store_id', 'smallint(5) unsigned not null default 0' );
	$connection->addKey( $productIndexPriceOptionTmpTable, 'PRIMARY', array( 'entity_id', 'customer_group_id', 'website_id', 'stock_id', 'currency', 'store_id' ), 'primary' );
	$connection->addColumn( $productIndexPriceDownloadableIdxTable, 'currency', 'varchar(3) null default null' );
	$connection->addColumn( $productIndexPriceDownloadableIdxTable, 'store_id', 'smallint(5) unsigned not null default 0' );
	$connection->addKey( $productIndexPriceDownloadableIdxTable, 'PRIMARY', array( 'entity_id', 'customer_group_id', 'website_id', 'stock_id', 'currency', 'store_id' ), 'primary' );
	$connection->addColumn( $productIndexPriceDownloadableTmpTable, 'currency', 'varchar(3) null default null' );
	$connection->addColumn( $productIndexPriceDownloadableTmpTable, 'store_id', 'smallint(5) unsigned not null default 0' );
	$connection->addKey( $productIndexPriceDownloadableTmpTable, 'PRIMARY', array( 'entity_id', 'customer_group_id', 'website_id', 'stock_id', 'currency', 'store_id' ), 'primary' );
	$connection->addColumn( $productIndexPriceCfgOptionAggregateIdxTable, 'currency', 'varchar(3) null default null' );
	$connection->addColumn( $productIndexPriceCfgOptionAggregateIdxTable, 'store_id', 'smallint(5) unsigned not null default 0' );
	$connection->addKey( $productIndexPriceCfgOptionAggregateIdxTable, 'PRIMARY', array( 'parent_id', 'child_id', 'customer_group_id', 'website_id', 'stock_id', 'currency', 'store_id' ), 'primary' );
	$connection->addColumn( $productIndexPriceCfgOptionAggregateTmpTable, 'currency', 'varchar(3) null default null' );
	$connection->addColumn( $productIndexPriceCfgOptionAggregateTmpTable, 'store_id', 'smallint(5) unsigned not null default 0' );
	$connection->addKey( $productIndexPriceCfgOptionAggregateTmpTable, 'PRIMARY', array( 'parent_id', 'child_id', 'customer_group_id', 'website_id', 'stock_id', 'currency', 'store_id' ), 'primary' );
	$connection->addColumn( $productIndexPriceCfgOptionIdxTable, 'currency', 'varchar(3) null default null' );
	$connection->addColumn( $productIndexPriceCfgOptionIdxTable, 'store_id', 'smallint(5) unsigned not null default 0' );
	$connection->addKey( $productIndexPriceCfgOptionIdxTable, 'PRIMARY', array( 'entity_id', 'customer_group_id', 'website_id', 'stock_id', 'currency', 'store_id' ), 'primary' );
	$connection->addColumn( $productIndexPriceCfgOptionTmpTable, 'currency', 'varchar(3) null default null' );
	$connection->addColumn( $productIndexPriceCfgOptionTmpTable, 'store_id', 'smallint(5) unsigned not null default 0' );
	$connection->addKey( $productIndexPriceCfgOptionTmpTable, 'PRIMARY', array( 'entity_id', 'customer_group_id', 'website_id', 'stock_id', 'currency', 'store_id' ), 'primary' );
	$installer->endSetup(  );
?>