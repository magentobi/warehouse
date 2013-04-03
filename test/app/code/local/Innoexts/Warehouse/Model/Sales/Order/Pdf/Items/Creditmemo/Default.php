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

	class Innoexts_Warehouse_Model_Sales_Order_Pdf_Items_Creditmemo_Default extends Mage_Sales_Model_Order_Pdf_Items_Creditmemo_Default {
		/**
     * Get warehouse helper
     *
     * @return  Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Draw process
     */
		function draw() {
			$this->getOrder(  );
			$item = $this->getItem(  );
			$pdf = $this->getPdf(  );
			$page = $this->getPage(  );
			$lines = array(  );
			$lines[0] = array( array( 'text' => Mage::helper( 'core/string' )->str_split( $item->getName(  ), 35, true, true ), 'feed' => 35 ) );
			$lines[0][] = array( 'text' => Mage::helper( 'core/string' )->str_split( $this->getSku( $item ), 17 ), 'feed' => 215, 'align' => 'right' );
			$lines[0][] = array( 'text' => $order->formatPriceTxt( $item->getRowTotal(  ) ), 'feed' => 280, 'font' => 'bold', 'align' => 'right' );
			$lines[0][] = array( 'text' => $order->formatPriceTxt( 0 - $item->getDiscountAmount(  ) ), 'feed' => 320, 'font' => 'bold', 'align' => 'right' );
			$lines[0][] = array( 'text' => $item->getQty(  ) * 1, 'feed' => 375, 'font' => 'bold', 'align' => 'right' );
			$lines[0][] = array( 'text' => $order->formatPriceTxt( $item->getTaxAmount(  ) ), 'feed' => 415, 'font' => 'bold', 'align' => 'right' );
			$subtotal = $item->getRowTotal(  ) + $item->getTaxAmount(  ) + $item->getHiddenTaxAmount(  ) - $item->getDiscountAmount(  );
			$lines[0][] = array( 'text' => $order->formatPriceTxt( $subtotal ), 'feed' => 475, 'font' => 'bold', 'align' => 'right' );
			$warehouse = ($item->getWarehouse(  ) ? $item->getWarehouseTitle(  ) : 'No warehouse');
			$lines[0][] = array( 'text' => Mage::helper( 'core/string' )->str_split( $warehouse, 25 ), 'feed' => 545, 'align' => 'right' );
			$options = $order = $this->getItemOptions(  );

			if ($options) {
				foreach ($options as $option) {
					$lines[][] = array( 'text' => Mage::helper( 'core/string' )->str_split( strip_tags( $option['label'] ), 40, true, true ), 'font' => 'italic', 'feed' => 35 );
					$_printValue = (isset( $option['print_value'] ) ? $option['print_value'] : strip_tags( $option['value'] ));
					$lines[][] = array( 'text' => Mage::helper( 'core/string' )->str_split( $_printValue, 30, true, true ), 'feed' => 40 );
				}
			}

			$lineBlock = array( 'lines' => $lines, 'height' => 20 );
			$page = $pdf->drawLineBlocks( $page, array( $lineBlock ), array( 'table_header' => true ) );
			$this->setPage( $page );
		}
	}

?>