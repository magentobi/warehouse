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

	class Innoexts_Warehouse_Model_Sales_Order extends Mage_Sales_Model_Order {
		private $_warehouses = null;

		/**
     * Get warehouse helper
     *
     * @return  Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Get stock identifiers
     * 
     * @return array
     */
		function getStockIds() {
			$stockIds = $this->getData( 'stock_ids' );

			if (is_null( $stockIds )) {
				$stockIds = array(  );
				foreach ($this->getAllItems(  ) as $stockId) {
					$item->getStockId(  );

					if ($stockId) {
						array_push( $stockIds, $stockId );
						continue;
					}
				}
			}

			return $stockIds;
		}

		/**
     * Get stock identifier
     * 
     * @return int
     */
		function getStockId() {
			$stockIds = $this->getStockIds(  );
			return array_shift( $stockIds );
		}

		/**
     * Get warehouses
     * 
     * @return array of Innoexts_Warehouse_Model_Warehouse
     */
		function getWarehouses() {
			$helper = $this->getWarehouseHelper(  );

			if (is_null( $this->_warehouses )) {
				$warehouses = array(  );
				foreach ($this->getStockIds(  ) as $stockId) {
					$warehouse = $helper->getWarehouseByStockId( $stockId );

					if ($warehouse) {
						$warehouses[$warehouse->getId(  )] = $warehouse;
						continue;
					}
				}

				$this->_warehouses = $helper->sortWarehouses( $warehouses );
			}

			return $this->_warehouses;
		}

		/**
     * Get warehouse titles
     * 
     * @return array
     */
		function getWarehouseTitles() {
			$titles = array(  );
			foreach ($this->getWarehouses(  ) as $warehouse) {
				array_push( $titles, $warehouse->getTitle(  ) );
			}

			return $titles;
		}

		/**
     * Get warehouse
     * 
     * @return Innoexts_Warehouse_Model_Warehouse
     */
		function getWarehouse() {
			$warehouses = $this->getWarehouses(  );

			if (count( $warehouses )) {
				return current( $warehouses );
			}

		}

		/**
     * Get warehouse title
     * 
     * @return string
     */
		function getWarehouseTitle() {
			$warehouse = $this->getWarehouse(  );

			if ($warehouse) {
				return $warehouse->getTitle(  );
			}

		}

		/**
     * Check if order has several warehouses
     * 
     * @return bool
     */
		function isMultipleWarehouse() {
			$warehouses = $this->getWarehouses(  );

			if (1 < count( $warehouses )) {
				return true;
			}

			return false;
		}

		/**
     * Clear order object data
     *
     * @param string $key data key
     * 
     * @return Innoexts_Warehouse_Model_Sales_Order
     */
		function unsetData($key = null) {
			parent::unsetData( $key );

			if (is_null( $key )) {
				$this->_warehouses = null;
				$this->unsData( 'stock_ids' );
			}

			return $this;
		}

		/**
     * Resets all data in object
     * so after another load it will be complete new object
     *
     * @return Innoexts_Warehouse_Model_Sales_Order
     */
		function reset() {
			parent::reset(  );
			$this->_warehouses = null;
			return $this;
		}

		/**
     * Send email with order data to warehouse
     *
     * @param Innoexts_Warehouse_Model_Warehouse $warehouse
     * 
     * @return Innoexts_Warehouse_Model_Sales_Order
     */
		function sendWarehouseNewOrderEmail($warehouse) {
			if (( ( !$warehouse || !$warehouse->isNotify(  ) ) || !$warehouse->isContactSet(  ) )) {
				return $this;
			}

			$this->getStore(  )->getId(  );

			if (!Mage::getStoreConfigFlag( XML_PATH_WAREHOUSE_EMAIL_ENABLED, $storeId )) {
				return $this;
			}

			$copyTo = $this->_getEmails( XML_PATH_WAREHOUSE_EMAIL_COPY_TO );
			$copyMethod = Mage::getStoreConfig( XML_PATH_WAREHOUSE_EMAIL_COPY_METHOD, $storeId );
			$appEmulation = $storeId = Mage::getSingleton( 'core/app_emulation' );
			$appEmulation->startEnvironmentEmulation( $storeId );
			$paymentBlock = Mage::helper( 'payment' )->getInfoBlock( $this->getPayment(  ) )->setIsSecureMode( true );
			$paymentBlock->getMethod(  )->setStore( $storeId );
			$paymentBlockHtml = $initialEnvironmentInfo = $paymentBlock->toHtml(  );
			jmp;
			Exception {
				$appEmulation->stopEnvironmentEmulation( $initialEnvironmentInfo );
				throw $exception;
				$appEmulation->stopEnvironmentEmulation( $initialEnvironmentInfo );

				if ($this->getCustomerIsGuest(  )) {
					$templateId = Mage::getStoreConfig( XML_PATH_WAREHOUSE_EMAIL_GUEST_TEMPLATE, $storeId );
				} 
else {
					$templateId = Mage::getStoreConfig( XML_PATH_WAREHOUSE_EMAIL_TEMPLATE, $storeId );
				}

				$mailer = Mage::getModel( 'core/email_template_mailer' );
				$emailInfo = Mage::getModel( 'core/email_info' );
				$emailInfo->addTo( $warehouse->getContactEmail(  ), $warehouse->getContactName(  ) );

				if (( $copyTo && $copyMethod == 'bcc' )) {
					foreach ($copyTo as $email) {
						$emailInfo->addBcc( $email );
					}
				}

				$mailer->addEmailInfo( $emailInfo );

				if (( $copyTo && $copyMethod == 'copy' )) {
					foreach ($copyTo as $email) {
						$emailInfo = Mage::getModel( 'core/email_info' );
						$emailInfo->addTo( $email );
						$mailer->addEmailInfo( $emailInfo );
					}
				}

				$mailer->setSender( Mage::getStoreConfig( XML_PATH_WAREHOUSE_EMAIL_IDENTITY, $storeId ) );
				$mailer->setStoreId( $storeId );
				$mailer->setTemplateId( $templateId );
				$mailer->setTemplateParams( array( 'order' => $this, 'warehouse' => $warehouse, 'billing' => $this->getBillingAddress(  ), 'payment_html' => $paymentBlockHtml ) );
				$mailer->send(  );
				return $this;
			}
		}

		/**
     * Send email with order data to warehouses
     * 
     * @return Innoexts_Warehouse_Model_Sales_Order
     */
		function sendWarehousesNewOrderEmail() {
			foreach ($this->getWarehouses(  ) as $warehouse) {
				$this->sendWarehouseNewOrderEmail( $warehouse );
			}

			return $this;
		}

		/**
     * Send email with order data
     *
     * @return Innoexts_Warehouse_Model_Sales_Order
     */
		function sendNewOrderEmail() {
			parent::sendNewOrderEmail(  );
			$this->sendWarehousesNewOrderEmail(  );
			return $this;
		}
	}

?>