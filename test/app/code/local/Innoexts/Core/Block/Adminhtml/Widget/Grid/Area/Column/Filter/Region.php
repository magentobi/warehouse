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

	class Innoexts_Core_Block_Adminhtml_Widget_Grid_Area_Column_Filter_Region extends Mage_Adminhtml_Block_Widget_Grid_Column_Filter_Text {
		/**
     * Get condition
     * 
     * @return array
     */
		function getCondition() {
			$value = trim( $this->getValue(  ) );

			if ($value == '*') {
				return array( 'null' => true );
			}

			return array( 'like' => '%' . $this->_escapeValue( $this->getValue(  ) ) . '%' );
		}
	}

?>