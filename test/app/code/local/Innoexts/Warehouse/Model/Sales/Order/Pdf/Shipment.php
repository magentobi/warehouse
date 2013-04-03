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

	class Innoexts_Warehouse_Model_Sales_Order_Pdf_Shipment extends Mage_Sales_Model_Order_Pdf_Shipment {
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
			$page->drawRectangle( 25, $this->y, 570, $this->y - 15 );
			 -= 'y';
			 = 10;
			( new Zend_Pdf_Color_RGB( 0, 0, 0 ) );
			$lines[0][] = array( 'text' => Mage::helper( 'sales' )->__( 'Qty' ), 'feed' => 35 );
			$lines[0][] = array( 'text' => Mage::helper( 'sales' )->__( 'Products' ), 'feed' => 100 );
			$lines[0][] = array( 'text' => Mage::helper( 'sales' )->__( 'SKU' ), 'feed' => 485, 'align' => 'right' );
			$lines[0][] = array( 'text' => $helper->__( 'Warehouse' ), 'feed' => 545, 'align' => 'right' );
			$lineBlock = array( 'lines' => $lines, 'height' => 10 );
			$this->drawLineBlocks( $page, array( $lineBlock ), array( 'table_header' => true ) );
			( new Zend_Pdf_Color_GrayScale( 0 ) );
			 -= 'y';
			 = 20;
		}

		/**
     * Return PDF document
     *
     * @param  array $shipments
     * @return Zend_Pdf
     */
		function getPdf($shipments = array(  )) {
			$helper = $this->getWarehouseHelper(  );
			$this->_beforeGetPdf(  );
			$this->_initRenderer( 'shipment' );
			$pdf = new Zend_Pdf(  );
			$this->_setPdf( $pdf );
			$style = new Zend_Pdf_Style(  );
			$this->_setFontBold( $style, 10 );
			foreach ($shipments as $shipment) {

				if ($shipment->getStoreId(  )) {
					Mage::app(  )->getLocale(  )->emulate( $shipment->getStoreId(  ) );
					Mage::app(  )->setCurrentStore( $shipment->getStoreId(  ) );
				}


				if ($helper->getVersionHelper(  )->isGe1700(  )) {
					$page = $this->newPage(  );
				} 
else {
					$page = $pdf->newPage( SIZE_A4 );
					$pdf->pages[] = $page;
				}

				$order = $shipment->getOrder(  );
				$this->insertLogo( $page, $shipment->getStore(  ) );
				$this->insertAddress( $page, $shipment->getStore(  ) );
				$this->insertOrder( $page, $shipment, Mage::getStoreConfigFlag( XML_PATH_SALES_PDF_SHIPMENT_PUT_ORDER_ID, $order->getStoreId(  ) ) );

				if ($helper->getVersionHelper(  )->isGe1700(  )) {
					$this->insertDocumentNumber( $page, Mage::helper( 'sales' )->__( 'Packingslip # ' ) . $shipment->getIncrementId(  ) );
				} 
else {
					( new Zend_Pdf_Color_GrayScale( 1 ) );
					$this->_setFontRegular( $page );
					$page->drawText( Mage::helper( 'sales' )->__( 'Packingslip # ' ) . $shipment->getIncrementId(  ), 35, 780, 'UTF-8' );
				}


				if ($helper->getVersionHelper(  )->isGe1700(  )) {
					$this->_drawHeader( $page );
				} 
else {
					( new Zend_Pdf_Color_RGB( 0.930000000000000048849813, 0.920000000000000039968029, 0.920000000000000039968029 ) );
					( new Zend_Pdf_Color_GrayScale( 0.5 ) );
					$page->setLineWidth( 0.5 );
					$page->drawRectangle( 25, $this->y, 570, $this->y - 15 );
					 -= 'y';
					 = 10;
					( new Zend_Pdf_Color_RGB( 0.40000000000000002220446, 0.40000000000000002220446, 0.40000000000000002220446 ) );
					$page->drawText( Mage::helper( 'sales' )->__( 'Qty' ), 35, $this->y, 'UTF-8' );
					$page->drawText( Mage::helper( 'sales' )->__( 'Products' ), 100, $this->y, 'UTF-8' );
					$page->drawText( Mage::helper( 'sales' )->__( 'SKU' ), 465, $this->y, 'UTF-8' );
					$page->drawText( $helper->__( 'Warehouse' ), 505, $this->y, 'UTF-8' );
					 -= 'y';
					 = 15;
					( new Zend_Pdf_Color_GrayScale( 0 ) );
				}

				foreach ($shipment->getAllItems(  ) as $item) {

					if ($item->getOrderItem(  )->getParentItem(  )) {
						continue;
					}


					if ($helper->getVersionHelper(  )->isGe1700(  )) {
						$this->_drawItem( $item, $page, $order );
						$page = end( $pdf->pages );
						continue;
					}


					if ($this->y < 15) {
						$page = $this->newPage( array( 'table_header' => true ) );
					}

					$page = $this->_drawItem( $item, $page, $order );
				}
			}

			$this->_afterGetPdf(  );

			if ($shipment->getStoreId(  )) {
				Mage::app(  )->getLocale(  )->revert(  );
			}

			return $pdf;
		}

		/**
     * Create new page and assign to PDF object
     *
     * @param  array $settings
     * @return Zend_Pdf_Page
     */
		function newPage($settings = array(  )) {
			$helper = $this->getWarehouseHelper(  );
			$page = $this->_getPdf(  )->newPage( SIZE_A4 );
			$this->_getPdf(  )->pages[] = $page;
			$this->y = 800;

			if (!empty( $settings['table_header'] )) {
				if ($helper->getVersionHelper(  )->isGe1700(  )) {
					$this->_drawHeader( $page );
				} 
else {
					$this->_setFontRegular( $page );
					( new Zend_Pdf_Color_RGB( 0.930000000000000048849813, 0.920000000000000039968029, 0.920000000000000039968029 ) );
					( new Zend_Pdf_Color_GrayScale( 0.5 ) );
					$page->setLineWidth( 0.5 );
					$page->drawRectangle( 25, $this->y, 570, $this->y - 15 );
					 -= 'y';
					 = 10;
					( new Zend_Pdf_Color_RGB( 0.40000000000000002220446, 0.40000000000000002220446, 0.40000000000000002220446 ) );
					$page->drawText( Mage::helper( 'sales' )->__( 'Qty' ), 35, $this->y, 'UTF-8' );
					$page->drawText( Mage::helper( 'sales' )->__( 'Products' ), 100, $this->y, 'UTF-8' );
					$page->drawText( Mage::helper( 'sales' )->__( 'SKU' ), 465, $this->y, 'UTF-8' );
					$page->drawText( $helper->__( 'Warehouse' ), 505, $this->y, 'UTF-8' );
					( new Zend_Pdf_Color_GrayScale( 0 ) );
					 -= 'y';
					 = 20;
				}
			}

			return $page;
		}
	}

?>