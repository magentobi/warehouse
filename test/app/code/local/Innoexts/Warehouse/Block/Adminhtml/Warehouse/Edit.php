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

	class Innoexts_Warehouse_Block_Adminhtml_Warehouse_Edit extends Innoexts_Core_Block_Adminhtml_Widget_Form_Container {
		private $_objectId = 'warehouse_id';
		private $_blockGroup = 'warehouse';
		private $_blockSubGroup = 'adminhtml';
		private $_controller = 'warehouse';
		private $_addLabel = 'New Warehouse';
		private $_editLabel = 'Edit Warehouse \'%s\'';
		private $_saveLabel = 'Save Warehouse';
		private $_saveAndContinueLabel = 'Save Warehouse and Continue Edit';
		private $_deleteLabel = 'Delete Warehouse';
		private $_saveAndContinueEnabled = true;
		private $_tabEnabled = true;
		private $_tabsBlockType = 'warehouse/adminhtml_warehouse_edit_tabs';
		private $_tabsBlockId = 'warehouse_tabs';
		private $_modelName = 'warehouse';

		/**
     * Retrieve warehouse helper
     *
     * @return Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Get default stock identifier
     * 
     * @return int
     */
		function getDefaultStockId() {
			return $this->getWarehouseHelper(  )->getDefaultStockId(  );
		}

		/**
     * Get stock identifier
     * 
     * @return int
     */
		function getStockId() {
			return $this->getModel(  )->getStockId(  );
		}

		/**
     * Check is allowed action
     * 
     * @param   string $action
     * @return  bool
     */
		function isAllowedAction($action) {
			if (( $action == 'delete' && $this->getStockId(  ) == $this->getDefaultStockId(  ) )) {
				return false;
			}

			return $this->getAdminSession(  )->isAllowed( 'catalog/warehouses/' . $action );
		}

		/**
     * Preparing block layout
     * 
     * @return Innoexts_Warehouse_Block_Adminhtml_Warehouse_Edit
     */
		function __prepareLayout() {
			parent::__prepareLayout(  );
			$this->_formScripts[] = 'var warehouseOriginModel = Class.create();
warehouseOriginModel.prototype = {
    initialize : function() {
        this.reload = false;
        this.loader = new varienLoader(true);
        this.regionsUrl = \'' . $this->getUrl( '*/*/regions' ) . '\';
        this.bindCountryRegionRelation();
    }, 
    bindCountryRegionRelation : function(parentId) {
        if(parentId) {var countryElements = $$(\'#\'+parentId+\' .origin_country_id\');}
        else {var countryElements = $$(\'.origin_country_id\');}
        for (var i=0; i<countryElements.size(); i++) {
            Event.observe(countryElements[i], \'change\', this.reloadRegionField.bind(this));
            this.initRegionField(countryElements[i]);
            if ($(countryElements[i].id+\'_inherit\')) {
                Event.observe($(countryElements[i].id+\'_inherit\'), \'change\', this.enableRegionZip.bind(this));
            }
        }
    }, 
    enableRegionZip : function(event) {
        this.reload = true;
        var countryElement = Event.element(event);
        if (countryElement && countryElement.id && !countryElement.checked) {
            var regionElement  = $(countryElement.id.replace(/country_id/, \'region_id\'));
            var zipElement  = $(countryElement.id.replace(/country_id/, \'postcode\'));
            if (regionElement && regionElement.checked) {regionElement.click();}
            if (zipElement && zipElement.checked) {zipElement.click();}
        }
    },
    initRegionField : function(element) {
        var countryElement = element;
        if (countryElement && countryElement.id) {
            var regionElement = $(countryElement.id.replace(/country_id/, \'region_id\'));
            if (regionElement) {
                this.regionElement = regionElement;
                var url = this.regionsUrl+\'parent/\'+countryElement.value;
                this.loader.load(url, {}, this.refreshRegionField.bind(this));
            }
        }
    },
    reloadRegionField : function(event) {
        this.reload = true;
        var countryElement = Event.element(event);
        if (countryElement && countryElement.id) {
            var regionElement  = $(countryElement.id.replace(/country_id/, \'region_id\'));
            if (regionElement) {
                this.regionElement = regionElement;
                var url = this.regionsUrl+\'parent/\'+countryElement.value;
                this.loader.load(url, {}, this.refreshRegionField.bind(this));
            }
        }
    },
    refreshRegionField : function(serverResponse) {
        if (serverResponse) {
            var data = eval(\'(\' + serverResponse + \')\');
            var value = this.regionElement.value;
            var disabled = this.regionElement.disabled;
            if (data.length) {
                var html = \'<select name="\'+this.regionElement.name+\'" id="\'+this.regionElement.id+\'" class="required-entry select" title="\'+this.regionElement.title+\'"\'+(disabled?" disabled":"")+\'>\';
                for (var i in data) {
                    if(data[i].label) {
                        html+= \'<option value="\'+data[i].value+\'"\';
                        if(this.regionElement.value && (this.regionElement.value == data[i].value || this.regionElement.value == data[i].label)) {
                            html+= \' selected\';
                        }
                        html+=\'>\'+data[i].label+\'<\/option>\';
                    }
                }
                html+= \'<\/select>\';
                var parentNode = this.regionElement.parentNode;
                var regionElementId = this.regionElement.id;
                parentNode.innerHTML = html;
                this.regionElement = $(regionElementId);
            } else if (this.reload) {
                var html = \'<input type="text" name="\'+this.regionElement.name+\'" id="\'+this.regionElement.id+\'" class="input-text" title="\'+this.regionElement.title+\'"\'+(disabled?" disabled":"")+\'>\';
                var parentNode = this.regionElement.parentNode;
                var regionElementId = this.regionElement.id;
                parentNode.innerHTML = html;
                this.regionElement = $(regionElementId);
                //this.regionElement.replace(html);
            }
        }
    }
}
warehouseOrigin = new warehouseOriginModel();';
			return $this;
		}
	}

?>