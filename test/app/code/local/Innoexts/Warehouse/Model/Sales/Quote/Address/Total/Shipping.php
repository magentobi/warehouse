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

	class Innoexts_Warehouse_Model_Sales_Quote_Address_Total_Shipping extends Mage_Sales_Model_Quote_Address_Total_Shipping {
		/**
     * Get warehouse helper
     *
     * @return  Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Add shipping totals information to address object
     *
     * @param Mage_Sales_Model_Quote_Address $address
     * 
     * @return Innoexts_Warehouse_Model_Sales_Quote_Address_Total_Shipping
     */
		function fetch($address) {
			$config = $this->getWarehouseHelper(  )->getConfig(  );

			if (( $config->isMultipleMode(  ) && $config->isSplitOrderEnabled(  ) )) {
				$amount = $address->getShippingAmount(  );

				if (( $amount != 0 || $address->getShippingDescription(  ) )) {
					$title = Mage::helper( 'sales' )->__( 'Shipping & Handling' );
					$address->addTotal( array( 'code' => $this->getCode(  ), 'title' => $title, 'value' => $address->getShippingAmount(  ) ) );
				}

				return $this;
			}

			parent::fetch( $address );
			return $this;
		}
	}

?>