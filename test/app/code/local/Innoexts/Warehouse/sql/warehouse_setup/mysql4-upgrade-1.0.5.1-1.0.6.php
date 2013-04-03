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

	$installer = $installer;
	$connection = $installer->getConnection(  );
	$installer->startSetup(  );
	$installer->removeAttribute( 'customer_address', 'warehouse_id' );
	$installer->removeAttribute( 'customer_address', 'stock_id' );
	$installer->endSetup(  );
?>