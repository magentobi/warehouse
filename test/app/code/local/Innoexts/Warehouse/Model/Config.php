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

	class Innoexts_Warehouse_Model_Config extends Varien_Object {
		/**
     * Get warehouse helper
     *
     * @return  Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Get mode
     * 
     * @return string
     */
		function getMode() {
			return Mage::getStoreConfig( XML_PATH_OPTIONS_MODE );
		}

		/**
     * Set mode
     * 
     * @param string $mode
     * 
     * @return Innoexts_Warehouse_Model_Config
     */
		function setMode($mode) {
			Mage::app(  )->getStore(  )->setConfig( XML_PATH_OPTIONS_MODE, $mode );
			return $this;
		}

		/**
     * Check if single mode is enabled
     * 
     * @return bool
     */
		function isSingleMode() {
			return ($this->getMode(  ) == 'single' ? true : false);
		}

		/**
     * Check if multiple mode is enabled
     * 
     * @return bool
     */
		function isMultipleMode() {
			return ($this->getMode(  ) == 'multiple' ? true : false);
		}

		/**
     * Check if information is visible
     * 
     * @return bool
     */
		function isInformationVisible() {
			return Mage::getStoreConfigFlag( XML_PATH_OPTIONS_DISPLAY_INFORMATION );
		}

		/**
     * Get sort by
     * 
     * @return bool
     */
		function getSortBy() {
			return Mage::getStoreConfig( XML_PATH_OPTIONS_SORT_BY );
		}

		/**
     * Check if sort by id
     * 
     * @return bool
     */
		function isSortById() {
			return ($this->getSortBy(  ) == 'id' ? true : false);
		}

		/**
     * Check if sort by code
     * 
     * @return bool
     */
		function isSortByCode() {
			return ($this->getSortBy(  ) == 'code' ? true : false);
		}

		/**
     * Check if sort by title
     * 
     * @return bool
     */
		function isSortByTitle() {
			return ($this->getSortBy(  ) == 'title' ? true : false);
		}

		/**
     * Check if sort by priority
     * 
     * @return bool
     */
		function isSortByPriority() {
			return ($this->getSortBy(  ) == 'priority' ? true : false);
		}

		/**
     * Check if sort by origin
     * 
     * @return bool
     */
		function isSortByOrigin() {
			return ($this->getSortBy(  ) == 'origin' ? true : false);
		}

		/**
     * Check if origin is visible
     * 
     * @return bool
     */
		function isOriginVisible() {
			return (( $this->isInformationVisible(  ) && Mage::getStoreConfigFlag( XML_PATH_OPTIONS_DISPLAY_ORIGIN ) ) ? true : false);
		}

		/**
     * Check if distance is visible
     * 
     * @return bool
     */
		function isDistanceVisible() {
			return (( $this->isInformationVisible(  ) && Mage::getStoreConfigFlag( XML_PATH_OPTIONS_DISPLAY_DISTANCE ) ) ? true : false);
		}

		/**
     * Get distance unit
     * 
     * @return string
     */
		function getDistanceUnit() {
			return Mage::getStoreConfig( XML_PATH_OPTIONS_DISTANCE_UNIT );
		}

		/**
     * Check if mile distance unit is enabled
     * 
     * @return bool
     */
		function isMileDistanceUnit() {
			return ($this->getDistanceUnit(  ) == 'mi' ? true : false);
		}

		/**
     * Check if kilometer distance unit is enabled
     * 
     * @return bool
     */
		function isKilometerDistanceUnit() {
			return ($this->getDistanceUnit(  ) == 'km' ? true : false);
		}

		/**
     * Check if description is visible
     * 
     * @return bool
     */
		function isDescriptionVisible() {
			return (( $this->isInformationVisible(  ) && Mage::getStoreConfigFlag( XML_PATH_OPTIONS_DISPLAY_DESCRIPTION ) ) ? true : false);
		}

		/**
     * Check if split order is enabled
     * 
     * @return bool
     */
		function isSplitOrderEnabled() {
			if ($this->isMultipleMode(  )) {
				return Mage::getStoreConfigFlag( XML_PATH_OPTIONS_SPLIT_ORDER );
			}

			return false;
		}

		/**
     * Check if split quantity is enabled
     * 
     * @return bool
     */
		function isSplitQtyEnabled() {
			if ($this->isMultipleMode(  )) {
				return Mage::getStoreConfigFlag( XML_PATH_OPTIONS_SPLIT_QTY );
			}

			return false;
		}

		/**
     * Check if force cart no backorders is enabled
     * 
     * @return bool
     */
		function isForceCartNoBackordersEnabled() {
			if ($this->isMultipleMode(  )) {
				return Mage::getStoreConfigFlag( XML_PATH_OPTIONS_FORCE_CART_NO_BACKORDERS );
			}

			return false;
		}

		/**
     * Check if force cart item no backorders is enabled
     * 
     * @return bool
     */
		function isForceCartItemNoBackordersEnabled() {
			if ($this->isMultipleMode(  )) {
				return Mage::getStoreConfigFlag( XML_PATH_OPTIONS_FORCE_CART_ITEM_NO_BACKORDERS );
			}

			return false;
		}

		/**
     * Get single assignment method code
     * 
     * @return string
     */
		function getSingleAssignmentMethodCode() {
			if ($this->isSingleMode(  )) {
				return Mage::getStoreConfig( XML_PATH_OPTIONS_SINGLE_ASSIGNMENT_METHOD );
			}

		}

		/**
     * Check if assigned areas is the current single assignment method
     * 
     * @return bool
     */
		function isAssignedAreaSingleAssignmentMethod() {
			return ($this->getSingleAssignmentMethodCode(  ) == 'assigned_area' ? true : false);
		}

		/**
     * Check if nearest is the current single assignment method
     * 
     * @return bool
     */
		function isNearestSingleAssignmentMethod() {
			return ($this->getSingleAssignmentMethodCode(  ) == 'nearest' ? true : false);
		}

		/**
     * Check if assigned store is the current single assignment method
     * 
     * @return bool
     */
		function isAssignedStoreSingleAssignmentMethod() {
			return ($this->getSingleAssignmentMethodCode(  ) == 'assigned_store' ? true : false);
		}

		/**
     * Check if assigned customer group is the current single assignment method
     * 
     * @return bool
     */
		function isAssignedCustomerGroupSingleAssignmentMethod() {
			return ($this->getSingleAssignmentMethodCode(  ) == 'assigned_customer_group' ? true : false);
		}

		/**
     * Check if assigned currency is the current single assignment method
     * 
     * @return bool
     */
		function isAssignedCurrencySingleAssignmentMethod() {
			return ($this->getSingleAssignmentMethodCode(  ) == 'assigned_currency' ? true : false);
		}

		/**
     * Check if manual is the current single assignment method
     * 
     * @return bool
     */
		function isManualSingleAssignmentMethod() {
			return ($this->getSingleAssignmentMethodCode(  ) == 'manual' ? true : false);
		}

		/**
     * Get multiple assignment method code
     * 
     * @return string
     */
		function getMultipleAssignmentMethodCode() {
			if ($this->isMultipleMode(  )) {
				return Mage::getStoreConfig( XML_PATH_OPTIONS_MULTIPLE_ASSIGNMENT_METHOD );
			}

		}

		/**
     * Check if lowest shipping is the current multiple assignment method
     * 
     * @return bool
     */
		function isLowestShippingMultipleAssignmentMethod() {
			return ($this->getMultipleAssignmentMethodCode(  ) == 'lowest_shipping' ? true : false);
		}

		/**
     * Check if lowest tax is the current multiple assignment method
     * 
     * @return bool
     */
		function isLowestTaxMultipleAssignmentMethod() {
			return ($this->getMultipleAssignmentMethodCode(  ) == 'lowest_tax' ? true : false);
		}

		/**
     * Check if lowest subtotal is the current multiple assignment method
     * 
     * @return bool
     */
		function isLowestSubtotalMultipleAssignmentMethod() {
			return ($this->getMultipleAssignmentMethodCode(  ) == 'lowest_subtotal' ? true : false);
		}

		/**
     * Check if lowest grand total is the current multiple assignment method
     * 
     * @return bool
     */
		function isLowestGrandTotalMultipleAssignmentMethod() {
			return ($this->getMultipleAssignmentMethodCode(  ) == 'lowest_grand_total' ? true : false);
		}

		/**
     * Check if nearest is the current multiple assignment method
     * 
     * @return bool
     */
		function isNearestMultipleAssignmentMethod() {
			return ($this->getMultipleAssignmentMethodCode(  ) == 'nearest' ? true : false);
		}

		/**
     * Check if priority is the current multiple assignment method
     * 
     * @return bool
     */
		function isPriorityMultipleAssignmentMethod() {
			return ($this->getMultipleAssignmentMethodCode(  ) == 'priority' ? true : false);
		}

		/**
     * Check if manual is the current multiple assignment method
     * 
     * @return bool
     */
		function isManualMultipleAssignmentMethod() {
			return ($this->getMultipleAssignmentMethodCode(  ) == 'manual' ? true : false);
		}

		/**
     * Check if adjustment is allowed
     * 
     * @return bool
     */
		function isAllowAdjustment() {
			$helper = $this->getWarehouseHelper(  );
			return (( ( ( $helper->isAdmin(  ) || Mage::getStoreConfigFlag( XML_PATH_OPTIONS_ALLOW_ADJUSTMENT ) ) || $this->isManualSingleAssignmentMethod(  ) ) || $this->isManualMultipleAssignmentMethod(  ) ) ? true : false);
		}

		/**
     * Check if priority is enabled
     * 
     * @return bool
     */
		function isPriorityEnabled() {
			return (( ( $this->isPriorityMultipleAssignmentMethod(  ) || $this->isSortByPriority(  ) ) || $this->isSplitQtyEnabled(  ) ) ? true : false);
		}

		/**
     * Check if catalog information visible
     * 
     * @return bool
     */
		function isCatalogInformationVisible() {
			return (( $this->isInformationVisible(  ) && Mage::getStoreConfigFlag( XML_PATH_CATALOG_DISPLAY_INFORMATION ) ) ? true : false);
		}

		/**
     * Check if catalog out of stock visible
     * 
     * @return bool
     */
		function isCatalogOutOfStockVisible() {
			return (( $this->isCatalogInformationVisible(  ) && Mage::getStoreConfigFlag( XML_PATH_CATALOG_DISPLAY_OUT_OF_STOCK ) ) ? true : false);
		}

		/**
     * Check if catalog origin visible
     * 
     * @return bool
     */
		function isCatalogOriginVisible() {
			return (( $this->isCatalogInformationVisible(  ) && Mage::getStoreConfigFlag( XML_PATH_CATALOG_DISPLAY_ORIGIN ) ) ? true : false);
		}

		/**
     * Check if catalog distance visible
     * 
     * @return bool
     */
		function isCatalogDistanceVisible() {
			return (( $this->isCatalogInformationVisible(  ) && Mage::getStoreConfigFlag( XML_PATH_CATALOG_DISPLAY_DISTANCE ) ) ? true : false);
		}

		/**
     * Check if catalog description visible
     * 
     * @return bool
     */
		function isCatalogDescriptionVisible() {
			return (( $this->isCatalogInformationVisible(  ) && Mage::getStoreConfigFlag( XML_PATH_CATALOG_DISPLAY_DESCRIPTION ) ) ? true : false);
		}

		/**
     * Check if catalog availability visible
     * 
     * @return bool
     */
		function isCatalogAvailabilityVisible() {
			return (( $this->isCatalogInformationVisible(  ) && Mage::getStoreConfigFlag( XML_PATH_CATALOG_DISPLAY_AVAILABILITY ) ) ? true : false);
		}

		/**
     * Check if catalog qty visible
     * 
     * @return bool
     */
		function isCatalogQtyVisible() {
			return (( $this->isCatalogInformationVisible(  ) && Mage::getStoreConfigFlag( XML_PATH_CATALOG_DISPLAY_QTY ) ) ? true : false);
		}

		/**
     * Check if catalog tax visible
     * 
     * @return bool
     */
		function isCatalogTaxVisible() {
			return (( $this->isCatalogInformationVisible(  ) && Mage::getStoreConfigFlag( XML_PATH_CATALOG_DISPLAY_TAX ) ) ? true : false);
		}

		/**
     * Check if catalog shipping visible
     * 
     * @return bool
     */
		function isCatalogShippingVisible() {
			return (( $this->isCatalogInformationVisible(  ) && Mage::getStoreConfigFlag( XML_PATH_CATALOG_DISPLAY_SHIPPING ) ) ? true : false);
		}

		/**
     * Check if catalog backend grid qty visible
     * 
     * @return bool
     */
		function isCatalogBackendGridQtyVisible() {
			return Mage::getStoreConfigFlag( XML_PATH_CATALOG_DISPLAY_BACKEND_GRID_QTY );
		}

		/**
     * Check if catalog backend grid batch prices visible
     * 
     * @return bool
     */
		function isCatalogBackendGridBatchPricesVisible() {
			return Mage::getStoreConfigFlag( XML_PATH_CATALOG_DISPLAY_BACKEND_GRID_BATCH_PRICES );
		}

		/**
     * Check if shelves function is enabled
     * 
     * @return bool
     */
		function isShelvesEnabled() {
			return Mage::getStoreConfigFlag( XML_PATH_CATALOG_ENABLE_SHELVES );
		}

		/**
     * Check if shipping methods filter is enabled
     * 
     * @return bool
     */
		function isShippingCarrierFilterEnabled() {
			return Mage::getStoreConfigFlag( XML_PATH_SHIPPING_ENABLE_CARRIER_FILTER );
		}
	}

?>