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

	$installer = $catalogProductIndexPriceTmpTable;
	$connection = $installer->getConnection(  );
	$installer->startSetup(  );
	$catalogInventoryStockTable = $installer->getTable( 'cataloginventory/stock' );
	$catalogProductIndexPriceTable = $installer->getTable( 'catalog/product_index_price' );
	$connection->addColumn( $catalogProductIndexPriceTable, 'stock_id', 'smallint(6) unsigned null default null' );
	$connection->addKey( $catalogProductIndexPriceTable, 'IDX_STOCK', array( 'stock_id' ), 'index' );
	$connection->addKey( $catalogProductIndexPriceTable, 'PRIMARY', array( 'entity_id', 'customer_group_id', 'website_id', 'stock_id' ), 'primary' );
	$connection->addConstraint( 'FK_CATALOG_PRODUCT_INDEX_PRICE_STOCK', $catalogProductIndexPriceTable, 'stock_id', $catalogInventoryStockTable, 'stock_id' );
	$catalogProductIndexPriceIdxTable = $installer->getTable( 'catalog/product_price_indexer_idx' );
	$connection->addColumn( $catalogProductIndexPriceIdxTable, 'stock_id', 'smallint(6) unsigned null default null' );
	$connection->addKey( $catalogProductIndexPriceIdxTable, 'IDX_STOCK', array( 'stock_id' ), 'index' );
	$connection->addKey( $catalogProductIndexPriceIdxTable, 'PRIMARY', array( 'entity_id', 'customer_group_id', 'website_id', 'stock_id' ), 'primary' );
	$catalogProductIndexPriceTmpTable = $installer->getTable( 'catalog/product_price_indexer_tmp' );
	$connection->addColumn( $catalogProductIndexPriceTmpTable, 'stock_id', 'smallint(6) unsigned null default null' );
	$connection->addKey( $catalogProductIndexPriceTmpTable, 'IDX_STOCK', array( 'stock_id' ), 'index' );
	$connection->addKey( $catalogProductIndexPriceTmpTable, 'PRIMARY', array( 'entity_id', 'customer_group_id', 'website_id', 'stock_id' ), 'primary' );
	$catalogProductIndexPriceFinalIdxTable = $installer->getTable( 'catalog/product_price_indexer_final_idx' );
	$connection->addColumn( $catalogProductIndexPriceFinalIdxTable, 'stock_id', 'smallint(6) unsigned null default null' );
	$connection->addKey( $catalogProductIndexPriceFinalIdxTable, 'IDX_STOCK', array( 'stock_id' ), 'index' );
	$connection->addKey( $catalogProductIndexPriceFinalIdxTable, 'PRIMARY', array( 'entity_id', 'customer_group_id', 'website_id', 'stock_id' ), 'primary' );
	$catalogProductIndexPriceFinalTmpTable = $installer->getTable( 'catalog/product_price_indexer_final_tmp' );
	$connection->addColumn( $catalogProductIndexPriceFinalTmpTable, 'stock_id', 'smallint(6) unsigned null default null' );
	$connection->addKey( $catalogProductIndexPriceFinalTmpTable, 'IDX_STOCK', array( 'stock_id' ), 'index' );
	$connection->addKey( $catalogProductIndexPriceFinalTmpTable, 'PRIMARY', array( 'entity_id', 'customer_group_id', 'website_id', 'stock_id' ), 'primary' );
	$catalogProductIndexPriceBundleIdxTable = $installer->getTable( 'bundle/price_indexer_idx' );
	$connection->addColumn( $catalogProductIndexPriceBundleIdxTable, 'stock_id', 'smallint(6) unsigned null default null' );
	$connection->addKey( $catalogProductIndexPriceBundleIdxTable, 'PRIMARY', array( 'entity_id', 'customer_group_id', 'website_id', 'stock_id' ), 'primary' );
	$catalogProductIndexPriceBundleTmpTable = $installer->getTable( 'bundle/price_indexer_tmp' );
	$connection->addColumn( $catalogProductIndexPriceBundleTmpTable, 'stock_id', 'smallint(6) unsigned null default null' );
	$connection->addKey( $catalogProductIndexPriceBundleTmpTable, 'PRIMARY', array( 'entity_id', 'customer_group_id', 'website_id', 'stock_id' ), 'primary' );
	$catalogProductIndexPriceBundleSelectionIdxTable = $installer->getTable( 'bundle/selection_indexer_idx' );
	$connection->addColumn( $catalogProductIndexPriceBundleSelectionIdxTable, 'stock_id', 'smallint(6) unsigned null default null' );
	$connection->addKey( $catalogProductIndexPriceBundleSelectionIdxTable, 'PRIMARY', array( 'entity_id', 'customer_group_id', 'website_id', 'option_id', 'selection_id', 'stock_id' ), 'primary' );
	$catalogProductIndexPriceBundleSelectionTmpTable = $installer->getTable( 'bundle/selection_indexer_tmp' );
	$connection->addColumn( $catalogProductIndexPriceBundleSelectionTmpTable, 'stock_id', 'smallint(6) unsigned null default null' );
	$connection->addKey( $catalogProductIndexPriceBundleSelectionTmpTable, 'PRIMARY', array( 'entity_id', 'customer_group_id', 'website_id', 'option_id', 'selection_id', 'stock_id' ), 'primary' );
	$catalogProductIndexPriceBundleOptionIdxTable = $installer->getTable( 'bundle/option_indexer_idx' );
	$connection->addColumn( $catalogProductIndexPriceBundleOptionIdxTable, 'stock_id', 'smallint(6) unsigned null default null' );
	$connection->addKey( $catalogProductIndexPriceBundleOptionIdxTable, 'PRIMARY', array( 'entity_id', 'customer_group_id', 'website_id', 'option_id', 'stock_id' ), 'primary' );
	$catalogProductIndexPriceBundleOptionTmpTable = $installer->getTable( 'bundle/option_indexer_tmp' );
	$connection->addColumn( $catalogProductIndexPriceBundleOptionTmpTable, 'stock_id', 'smallint(6) unsigned null default null' );
	$connection->addKey( $catalogProductIndexPriceBundleOptionTmpTable, 'PRIMARY', array( 'entity_id', 'customer_group_id', 'website_id', 'option_id', 'stock_id' ), 'primary' );
	$catalogProductIndexPriceOptionAggregateIdxTable = $installer->getTable( 'catalog/product_price_indexer_option_aggregate_idx' );
	$connection->addColumn( $catalogProductIndexPriceOptionAggregateIdxTable, 'stock_id', 'smallint(6) unsigned null default null' );
	$connection->addKey( $catalogProductIndexPriceOptionAggregateIdxTable, 'PRIMARY', array( 'entity_id', 'customer_group_id', 'website_id', 'option_id', 'stock_id' ), 'primary' );
	$catalogProductIndexPriceOptionAggregateTmpTable = $installer->getTable( 'catalog/product_price_indexer_option_aggregate_tmp' );
	$connection->addColumn( $catalogProductIndexPriceOptionAggregateTmpTable, 'stock_id', 'smallint(6) unsigned null default null' );
	$connection->addKey( $catalogProductIndexPriceOptionAggregateTmpTable, 'PRIMARY', array( 'entity_id', 'customer_group_id', 'website_id', 'option_id', 'stock_id' ), 'primary' );
	$catalogProductIndexPriceOptionIdxTable = $installer->getTable( 'catalog/product_price_indexer_option_idx' );
	$connection->addColumn( $catalogProductIndexPriceOptionIdxTable, 'stock_id', 'smallint(6) unsigned null default null' );
	$connection->addKey( $catalogProductIndexPriceOptionIdxTable, 'PRIMARY', array( 'entity_id', 'customer_group_id', 'website_id', 'stock_id' ), 'primary' );
	$catalogProductIndexPriceOptionTmpTable = $installer->getTable( 'catalog/product_price_indexer_option_tmp' );
	$connection->addColumn( $catalogProductIndexPriceOptionTmpTable, 'stock_id', 'smallint(6) unsigned null default null' );
	$connection->addKey( $catalogProductIndexPriceOptionTmpTable, 'PRIMARY', array( 'entity_id', 'customer_group_id', 'website_id', 'stock_id' ), 'primary' );
	$catalogProductIndexPriceDownloadableIdxTable = $installer->getTable( 'downloadable/product_price_indexer_idx' );
	$connection->addColumn( $catalogProductIndexPriceDownloadableIdxTable, 'stock_id', 'smallint(6) unsigned null default null' );
	$connection->addKey( $catalogProductIndexPriceDownloadableIdxTable, 'PRIMARY', array( 'entity_id', 'customer_group_id', 'website_id', 'stock_id' ), 'primary' );
	$catalogProductIndexPriceDownloadableTmpTable = $installer->getTable( 'downloadable/product_price_indexer_tmp' );
	$connection->addColumn( $catalogProductIndexPriceDownloadableTmpTable, 'stock_id', 'smallint(6) unsigned null default null' );
	$connection->addKey( $catalogProductIndexPriceDownloadableTmpTable, 'PRIMARY', array( 'entity_id', 'customer_group_id', 'website_id', 'stock_id' ), 'primary' );
	$catalogProductIndexPriceCfgOptionAggregateIdxTable = $installer->getTable( 'catalog/product_price_indexer_cfg_option_aggregate_idx' );
	$connection->addColumn( $catalogProductIndexPriceCfgOptionAggregateIdxTable, 'stock_id', 'smallint(6) unsigned null default null' );
	$connection->addKey( $catalogProductIndexPriceCfgOptionAggregateIdxTable, 'PRIMARY', array( 'parent_id', 'child_id', 'customer_group_id', 'website_id', 'stock_id' ), 'primary' );
	$catalogProductIndexPriceCfgOptionAggregateTmpTable = $installer->getTable( 'catalog/product_price_indexer_cfg_option_aggregate_tmp' );
	$connection->addColumn( $catalogProductIndexPriceCfgOptionAggregateTmpTable, 'stock_id', 'smallint(6) unsigned null default null' );
	$connection->addKey( $catalogProductIndexPriceCfgOptionAggregateTmpTable, 'PRIMARY', array( 'parent_id', 'child_id', 'customer_group_id', 'website_id', 'stock_id' ), 'primary' );
	$catalogProductIndexPriceCfgOptionIdxTable = $installer->getTable( 'catalog/product_price_indexer_cfg_option_idx' );
	$connection->addColumn( $catalogProductIndexPriceCfgOptionIdxTable, 'stock_id', 'smallint(6) unsigned null default null' );
	$connection->addKey( $catalogProductIndexPriceCfgOptionIdxTable, 'PRIMARY', array( 'entity_id', 'customer_group_id', 'website_id', 'stock_id' ), 'primary' );
	$catalogProductIndexPriceCfgOptionTmpTable = $installer->getTable( 'catalog/product_price_indexer_cfg_option_tmp' );
	$connection->addColumn( $catalogProductIndexPriceCfgOptionTmpTable, 'stock_id', 'smallint(6) unsigned null default null' );
	$connection->addKey( $catalogProductIndexPriceCfgOptionTmpTable, 'PRIMARY', array( 'entity_id', 'customer_group_id', 'website_id', 'stock_id' ), 'primary' );
	$installer->endSetup(  );
?>