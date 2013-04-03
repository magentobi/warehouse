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

	class Innoexts_Core_Block_Adminhtml_Widget_Grid_Area_Column_Renderer_Zip extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Text {
		/**
     * Get value
     * 
     * @return string
     */
		function _getValue($row) {
			$value = parent::_getValue( $row );

			if ($value === '') {
				return '*';
			}

			return $value;
		}
	}

?>