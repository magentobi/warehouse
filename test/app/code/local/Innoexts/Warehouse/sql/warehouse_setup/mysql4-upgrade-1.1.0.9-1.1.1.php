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
	$installer->startSetup(  );
	$quoteTable = $installer->getTable( 'sales/quote' );
	$connection->changeColumn( $quoteTable, 'qtys', 'items_qtys', 'text null default null' );
	$installer->endSetup(  );
?>