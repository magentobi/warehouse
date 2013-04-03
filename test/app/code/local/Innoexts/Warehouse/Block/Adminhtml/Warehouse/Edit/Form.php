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

	class Innoexts_Warehouse_Block_Adminhtml_Warehouse_Edit_Form extends Mage_Adminhtml_Block_Widget_Form {
		/**
     * Prepare form before rendering HTML
     *
     * @return Innoexts_Warehouse_Block_Adminhtml_Warehouse_Edit_Form
     */
		function _prepareForm() {
			$attributes = array( 'id' => 'edit_form', 'action' => $this->getData( 'action' ), 'method' => 'post' );
			$form = new Varien_Data_Form( $attributes );
			$form->setUseContainer( true );
			$this->setForm( $form );
			return parent::_prepareForm(  );
		}
	}

?>