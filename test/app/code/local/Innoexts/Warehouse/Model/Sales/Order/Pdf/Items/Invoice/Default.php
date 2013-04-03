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

	class Innoexts_Warehouse_Model_Sales_Order_Pdf_Items_Invoice_Default extends Mage_Sales_Model_Order_Pdf_Items_Invoice_Default {
		/**
     * Get warehouse helper
     *
     * @return  Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Draw item line
     */
		function draw() {
			$helper = $this->getWarehouseHelper(  );
			$order = $this->getOrder(  );
			$item = $this->getItem(  );
			$pdf = $this->getPdf(  );
			$page = $this->getPage(  );
			$lines = array(  );
			$lines[0] = array( array( 'text' => Mage::helper( 'core/string' )->str_split( $item->getName(  ), 35, true, true ), 'feed' => 35 ) );
			$lines[0][] = array( 'text' => Mage::helper( 'core/string' )->str_split( $this->getSku( $item ), 17 ), 'feed' => 250, 'align' => 'right' );
			$lines[0][] = array( 'text' => $item->getQty(  ) * 1, 'feed' => 375, 'align' => 'right' );

			if ($helper->getVersionHelper(  )->isGe1700(  )) {
				$i = 47;
				$prices = $this->getItemPricesForDisplay(  );
				$feedPrice = 387;
				$feedSubtotal = 542;
				foreach ($prices as $priceData) {

					if (isset( $priceData['label'] )) {
						$lines[$i][] = array( 'text' => $priceData['label'], 'feed' => $feedPrice, 'align' => 'right' );
						$lines[$i][] = array( 'text' => $priceData['label'], 'feed' => $feedSubtotal, 'align' => 'right' );
						++$i;
					}

					$lines[$i][] = array( 'text' => $priceData['price'], 'feed' => $feedPrice, 'font' => 'bold', 'align' => 'right' );
					$lines[$i][] = array( 'text' => $priceData['subtotal'], 'feed' => $feedSubtotal, 'font' => 'bold', 'align' => 'right' );
					++$i;
				}
			} 
else {
				$lines[0][] = array( 'text' => $order->formatPriceTxt( $item->getPrice(  ) ), 'feed' => 340, 'font' => 'bold', 'align' => 'right' );
				$lines[0][] = array( 'text' => $order->formatPriceTxt( $item->getRowTotal(  ) ), 'feed' => 495, 'font' => 'bold', 'align' => 'right' );
			}

			$lines[0][] = array( 'text' => $order->formatPriceTxt( $item->getTaxAmount(  ) ), 'feed' => 425, 'font' => 'bold', 'align' => 'right' );
			$warehouse = ($item->getWarehouse(  ) ? $item->getWarehouseTitle(  ) : 'No warehouse');
			$lines[0][] = array( 'text' => Mage::helper( 'core/string' )->str_split( $warehouse, 25 ), 'feed' => 545, 'align' => 'right' );
			$options = $this->getItemOptions(  );

			if ($options) {
				foreach ($options as $option) {
					$lines[][] = array( 'text' => Mage::helper( 'core/string' )->str_split( strip_tags( $option['label'] ), 40, true, true ), 'font' => 'italic', 'feed' => 35 );

					if ($option['value']) {
						if (isset( $option['print_value'] )) {
							$_printValue = $option['print_value'];
						} 
else {
							$_printValue = strip_tags( $option['value'] );
						}

						$values = explode( ', ', $_printValue );
						foreach ($values as $value) {
							$lines[][] = array( 'text' => Mage::helper( 'core/string' )->str_split( $value, 30, true, true ), 'feed' => 40 );
						}

						continue;
					}
				}
			}

			$lineBlock = array( 'lines' => $lines, 'height' => 20 );
			$page = $pdf->drawLineBlocks( $page, array( $lineBlock ), array( 'table_header' => true ) );
			$this->setPage( $page );
		}
	}

?>