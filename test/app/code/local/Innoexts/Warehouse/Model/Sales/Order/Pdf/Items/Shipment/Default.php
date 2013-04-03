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

	class Innoexts_Warehouse_Model_Sales_Order_Pdf_Items_Shipment_Default extends Mage_Sales_Model_Order_Pdf_Items_Shipment_Default {
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
			$item = $this->getItem(  );
			$pdf = $this->getPdf(  );
			$page = $this->getPage(  );
			$lines = array(  );
			$lines[0][] = array( 'text' => $item->getQty(  ) * 1, 'feed' => 35 );
			$lines[0] = array( array( 'text' => Mage::helper( 'core/string' )->str_split( $item->getName(  ), 60, true, true ), 'feed' => 100 ) );
			$lines[0][] = array( 'text' => Mage::helper( 'core/string' )->str_split( $this->getSku( $item ), 25 ), 'feed' => 485, 'align' => 'right' );
			$warehouse = ($item->getWarehouse(  ) ? $item->getWarehouseTitle(  ) : 'No warehouse');
			$lines[0][] = array( 'text' => Mage::helper( 'core/string' )->str_split( $warehouse, 25 ), 'feed' => 545, 'align' => 'right' );
			$options = $this->getItemOptions(  );

			if ($options) {
				foreach ($options as $option) {
					$lines[][] = array( 'text' => Mage::helper( 'core/string' )->str_split( strip_tags( $option['label'] ), 70, true, true ), 'font' => 'italic', 'feed' => 110 );

					if ($option['value']) {
						$_printValue = (isset( $option['print_value'] ) ? $option['print_value'] : strip_tags( $option['value'] ));
						$values = explode( ', ', $_printValue );
						foreach ($values as $value) {
							$lines[][] = array( 'text' => Mage::helper( 'core/string' )->str_split( $value, 50, true, true ), 'feed' => 115 );
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