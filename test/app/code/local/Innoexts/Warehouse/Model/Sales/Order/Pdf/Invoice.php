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

	class Innoexts_Warehouse_Model_Sales_Order_Pdf_Invoice extends Mage_Sales_Model_Order_Pdf_Invoice {
		/**
     * Get warehouse helper
     *
     * @return  Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Draw header for item table
     *
     * @param Zend_Pdf_Page $page
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
			$lines[0][] = array( 'text' => Mage::helper( 'sales' )->__( 'Products' ), 'feed' => 35 );
			$lines[0][] = array( 'text' => Mage::helper( 'sales' )->__( 'SKU' ), 'feed' => 250, 'align' => 'right' );
			$lines[0][] = array( 'text' => Mage::helper( 'sales' )->__( 'Price' ), 'feed' => 310, 'align' => 'right' );
			$lines[0][] = array( 'text' => Mage::helper( 'sales' )->__( 'Qty' ), 'feed' => 375, 'align' => 'right' );
			$lines[0][] = array( 'text' => Mage::helper( 'sales' )->__( 'Tax' ), 'feed' => 425, 'align' => 'right' );
			$lines[0][] = array( 'text' => Mage::helper( 'sales' )->__( 'Subtotal' ), 'feed' => 485, 'align' => 'right' );
			$lines[0][] = array( 'text' => $helper->__( 'Warehouse' ), 'feed' => 545, 'align' => 'right' );
			$lineBlock = array( 'lines' => $lines, 'height' => 5 );
			$this->drawLineBlocks( $page, array( $lineBlock ), array( 'table_header' => true ) );
			( new Zend_Pdf_Color_GrayScale( 0 ) );
			 -= 'y';
			 = 20;
		}

		/**
     * Get pdf
     * 
     * @param array $invoices
     * 
     * @return Zend_Pdf
     */
		function getPdf($invoices = array(  )) {
			$helper = $this->getWarehouseHelper(  );
			$this->_beforeGetPdf(  );
			$this->_initRenderer( 'invoice' );
			$pdf = new Zend_Pdf(  );
			$this->_setPdf( $pdf );
			$style = new Zend_Pdf_Style(  );
			$this->_setFontBold( $style, 10 );
			foreach ($invoices as $invoice) {

				if ($invoice->getStoreId(  )) {
					Mage::app(  )->getLocale(  )->emulate( $invoice->getStoreId(  ) );
					Mage::app(  )->setCurrentStore( $invoice->getStoreId(  ) );
				}


				if ($helper->getVersionHelper(  )->isGe1700(  )) {
					$page = $this->newPage(  );
				} 
else {
					$page = $pdf->newPage( SIZE_A4 );
					$pdf->pages[] = $page;
				}

				$order = $invoice->getOrder(  );
				$this->insertLogo( $page, $invoice->getStore(  ) );
				$this->insertAddress( $page, $invoice->getStore(  ) );
				$this->insertOrder( $page, $order, Mage::getStoreConfigFlag( XML_PATH_SALES_PDF_INVOICE_PUT_ORDER_ID, $order->getStoreId(  ) ) );

				if ($helper->getVersionHelper(  )->isGe1700(  )) {
					$this->insertDocumentNumber( $page, Mage::helper( 'sales' )->__( 'Invoice # ' ) . $invoice->getIncrementId(  ) );
				} 
else {
					( new Zend_Pdf_Color_GrayScale( 1 ) );
					$this->_setFontRegular( $page );
					$page->drawText( Mage::helper( 'sales' )->__( 'Invoice # ' ) . $invoice->getIncrementId(  ), 35, 780, 'UTF-8' );
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
					$page->drawText( Mage::helper( 'sales' )->__( 'Products' ), 35, $this->y, 'UTF-8' );
					$page->drawText( Mage::helper( 'sales' )->__( 'SKU' ), 215, $this->y, 'UTF-8' );
					$page->drawText( Mage::helper( 'sales' )->__( 'Price' ), 330, $this->y, 'UTF-8' );
					$page->drawText( Mage::helper( 'sales' )->__( 'Qty' ), 370, $this->y, 'UTF-8' );
					$page->drawText( Mage::helper( 'sales' )->__( 'Tax' ), 410, $this->y, 'UTF-8' );
					$page->drawText( Mage::helper( 'sales' )->__( 'Subtotal' ), 455, $this->y, 'UTF-8' );
					$page->drawText( $helper->__( 'Warehouse' ), 495, $this->y, 'UTF-8' );
					 -= 'y';
					 = 15;
					( new Zend_Pdf_Color_GrayScale( 0 ) );
				}

				foreach ($invoice->getAllItems(  ) as $item) {

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


				if ($helper->getVersionHelper(  )->isGe1700(  )) {
					$this->insertTotals( $page, $invoice );
				} 
else {
					$page = $this->insertTotals( $page, $invoice );
				}


				if ($invoice->getStoreId(  )) {
					Mage::app(  )->getLocale(  )->revert(  );
					continue;
				}
			}

			$this->_afterGetPdf(  );
			return $pdf;
		}

		/**
     * Create new page and assign to PDF object
     *
     * @param  array $settings
     * @return Zend_Pdf_Page
     */
		function newPage($settings = array(  )) {
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
					$page->drawText( Mage::helper( 'sales' )->__( 'Products' ), 35, $this->y, 'UTF-8' );
					$page->drawText( Mage::helper( 'sales' )->__( 'SKU' ), 215, $this->y, 'UTF-8' );
					$page->drawText( Mage::helper( 'sales' )->__( 'Price' ), 330, $this->y, 'UTF-8' );
					$page->drawText( Mage::helper( 'sales' )->__( 'Qty' ), 370, $this->y, 'UTF-8' );
					$page->drawText( Mage::helper( 'sales' )->__( 'Tax' ), 410, $this->y, 'UTF-8' );
					$page->drawText( Mage::helper( 'sales' )->__( 'Subtotal' ), 455, $this->y, 'UTF-8' );
					$page->drawText( $helper->__( 'Warehouse' ), 495, $this->y, 'UTF-8' );
					( new Zend_Pdf_Color_GrayScale( 0 ) );
					 -= 'y';
					 = 20;
				}
			}

			return $page;
		}
	}

?>