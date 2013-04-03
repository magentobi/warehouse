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

	class Innoexts_Core_Block_Adminhtml_Widget_Grid_Editable_Area_Container extends Innoexts_Core_Block_Adminhtml_Widget_Grid_Editable_Container {
		/**
     * Constructor
     */
		function __construct() {
			parent::__construct(  );
			$this->setTemplate( 'innoexts/core/widget/grid/editable/area/container.phtml' );
		}

		/**
     * Retrieve regions in JSON format
     * 
     * @return string
     */
		function getRegionsJson() {
			return Mage::helper( 'innoexts_core' )->getDirectoryHelper(  )->getRegionJson2(  );
		}
	}

?>