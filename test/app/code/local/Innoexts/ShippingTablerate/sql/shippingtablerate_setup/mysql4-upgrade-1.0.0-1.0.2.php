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

	$installer = $connection;
	$connection = $installer->getConnection(  );
	$shippingTablerate = $installer->getTable( 'shippingtablerate/tablerate' );
	$installer->startSetup(  );
	$connection->addColumn( $shippingTablerate, 'note', 'text null default null' );
	$installer->endSetup(  );
?>