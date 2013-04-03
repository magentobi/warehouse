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

	class Innoexts_Warehouse_Model_Sales_Order_Pdf_Creditmemo extends Mage_Sales_Model_Order_Pdf_Creditmemo {
		/**
     * Get warehouse helper
     *
     * @return  Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Draw table header for product items
     *
     * @param  Zend_Pdf_Page $page
     * @return void
     */
		function _drawHeader($page) {
			$helper = $this->getWarehouseHelper(  );
			$this->_setFontRegular( $page, 10 );
			( new Zend_Pdf_Color_RGB( 0.930000000000000048849813, 0.920000000000000039968029, 0.920000000000000039968029 ) );
			( new Zend_Pdf_Color_GrayScale( 0.5 ) );
			$page->setLineWidth( 0.5 );
			$page->drawRectangle( 25, $this->y, 570, $this->y - 30 );
			 -= 'y';
			 = 10;
			( new Zend_Pdf_Color_RGB( 0, 0, 0 ) );
			$lines[0][] = array( 'text' => Mage::helper( 'sales' )->__( 'Products' ), 'feed' => 35 );
			$lines[0][] = array( 'text' => Mage::helper( 'core/string' )->str_split( Mage::helper( 'sales' )->__( 'SKU' ), 12, true, true ), 'feed' => 215, 'align' => 'right' );
			$lines[0][] = array( 'text' => Mage::helper( 'core/string' )->str_split( Mage::helper( 'sales' )->__( 'Total (ex)' ), 12, true, true ), 'feed' => 280, 'align' => 'right' );
			$lines[0][] = array( 'text' => Mage::helper( 'core/string' )->str_split( Mage::helper( 'sales' )->__( 'Discount' ), 12, true, true ), 'feed' => 320, 'align' => 'right' );
			$lines[0][] = array( 'text' => Mage::helper( 'core/string' )->str_split( Mage::helper( 'sales' )->__( 'Qty' ), 12, true, true ), 'feed' => 375, 'align' => 'right' );
			$lines[0][] = array( 'text' => Mage::helper( 'core/string' )->str_split( Mage::helper( 'sales' )->__( 'Tax' ), 12, true, true ), 'feed' => 415, 'align' => 'right' );
			$lines[0][] = array( 'text' => Mage::helper( 'core/string' )->str_split( Mage::helper( 'sales' )->__( 'Total (inc)' ), 12, true, true ), 'feed' => 475, 'align' => 'right' );
			$lines[0][] = array( 'text' => $helper->__( 'Warehouse' ), 'feed' => 545, 'align' => 'right' );
			$lineBlock = array( 'lines' => $lines, 'height' => 10 );
			$this->drawLineBlocks( $page, array( $lineBlock ), array( 'table_header' => true ) );
			( new Zend_Pdf_Color_GrayScale( 0 ) );
			 -= 'y';
			 = 20;
		}
	}

?>