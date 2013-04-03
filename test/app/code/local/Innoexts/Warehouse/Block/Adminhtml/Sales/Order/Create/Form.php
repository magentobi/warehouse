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

	class Innoexts_Warehouse_Block_Adminhtml_Sales_Order_Create_Form extends Mage_Adminhtml_Block_Sales_Order_Create_Form {
		/**
     * Before to html
     * 
     * return Innoexts_Warehouse_Block_Adminhtml_Sales_Order_Create_Form
     */
		function _beforeToHtml() {
			$this->setTemplate( 'warehouse/sales/order/create/form.phtml' );
			parent::_beforeToHtml(  );
			return $this;
		}
	}

?>