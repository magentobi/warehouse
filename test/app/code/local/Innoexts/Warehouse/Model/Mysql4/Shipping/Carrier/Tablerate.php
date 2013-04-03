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

	class Innoexts_Warehouse_Model_Mysql4_Shipping_Carrier_Tablerate extends Mage_Shipping_Model_Mysql4_Carrier_Tablerate {
		private $_importWarehouses = null;

		/**
     * Get warehouse helper
     *
     * @return  Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Get version helper
     * 
     * @return Innoexts_Core_Helper_Version
     */
		function getVersionHelper() {
			return $this->getWarehouseHelper(  )->getVersionHelper(  );
		}

		/**
     * Load warehouses
     *
     * @return Innoexts_Warehouse_Model_Mysql4_Shipping_Carrier_Tablerate
     */
		function _loadWarehouses() {
			if (( !is_null( $this->_importWarehouses ) && !is_null( $this->_importWarehouses ) )) {
				return $this;
			}

			$this->_importWarehouses = Mage::getSingleton( 'warehouse/warehouse' )->getCollection(  )->toOptionHash(  );
			return $this;
		}

		/**
     * Return table rate array or false by rate request
     *
     * @param Mage_Shipping_Model_Rate_Request $request
     * 
     * @return array|false
     */
		function getRate($request) {
			$adapter = $this->_getReadAdapter(  );
			$bind = array( ':website_id' => (int)$request->getWebsiteId(  ), ':country_id' => $request->getDestCountryId(  ), ':region_id' => $request->getDestRegionId(  ), ':postcode' => $request->getDestPostcode(  ), ':warehouse_id' => (int)$request->getWarehouseId(  ) );
			$order = array( 'dest_country_id DESC', 'dest_region_id DESC', 'dest_zip DESC' );

			if (is_array( $request->getConditionName(  ) )) {
				$pieces = array(  );
				foreach ($request->getConditionName(  ) as $index => $conditionName) {
					array_push( $pieces, ( 'WHEN condition_name = \'' . $conditionName . '\' THEN \'' . $index . '\'' ) );
				}

				array_push( $order, '(CASE ' . implode( ' ', $pieces ) . ' END) ASC' );
			}

			array_push( $order, 'condition_value DESC' );
			array_push( $order, 'warehouse_id DESC' );
			$select = $adapter->select(  )->from( $this->getMainTable(  ) )->where( 'website_id = :website_id' )->where( '(warehouse_id = :warehouse_id OR warehouse_id = \'0\')' )->order( $order )->limit( 1 );
			$orWhere = '(' . implode( ') OR (', array( 'dest_country_id = :country_id AND dest_region_id = :region_id AND dest_zip = :postcode', 'dest_country_id = :country_id AND dest_region_id = :region_id AND (dest_zip = \'\' OR dest_zip = \'*\')', 'dest_country_id = :country_id AND dest_region_id = 0 AND (dest_zip = \'\' OR dest_zip = \'*\')', 'dest_country_id = :country_id AND dest_region_id = 0 AND dest_zip = :postcode', 'dest_country_id = \'0\' AND dest_region_id = 0 AND (dest_zip = \'\' OR dest_zip = \'*\')' ) ) . ')';
			$select->where( $orWhere );

			if (is_array( $request->getConditionName(  ) )) {
				$orWhere = array(  );
				$i = 178;
				foreach ($request->getConditionName(  ) as $conditionName) {
					$bindNameKey = sprintf( ':condition_name_%d', $i );
					$bindValueKey = sprintf( ':condition_value_%d', $i );
					$orWhere[] = ( '(condition_name = ' . $bindNameKey . ' AND condition_value <= ' . $bindValueKey . ')' );
					$bind[$bindNameKey] = $conditionName;
					$bind[$bindValueKey] = $request->getData( $conditionName );
					++$i;
				}


				if ($orWhere) {
					$select->where( implode( ' OR ', $orWhere ) );
				}
			} 
else {
				$bind[':condition_name'] = $request->getConditionName(  );
				$bind[':condition_value'] = $request->getData( $request->getConditionName(  ) );
				$select->where( 'condition_name = :condition_name' );
				$select->where( 'condition_value <= :condition_value' );
			}

			return $adapter->fetchRow( $select, $bind );
		}

		/**
     * Upload table rate file and import data from it
     *
     * @param Varien_Object $object
     * @throws Mage_Core_Exception
     * 
     * @return Innoexts_Warehouse_Model_Mysql4_Shipping_Carrier_Tablerate
     */
		function uploadAndImport($object) {
			if (empty( $_FILES['groups']['tmp_name']['tablerate']['fields']['import']['value'] )) {
				return $this;
			}

			$csvFile = $_FILES['groups']['tmp_name']['tablerate']['fields']['import']['value'];
			$website = Mage::app(  )->getWebsite( $object->getScopeId(  ) );
			$this->_importWebsiteId = (int)$website->getId(  );
			$this->_importUniqueHash = array(  );
			$this->_importErrors = array(  );
			$this->_importedRows = 0;
			$io = new Varien_Io_File(  );
			$info = pathinfo( $csvFile );
			$io->open( array( 'path' => $info['dirname'] ) );
			$io->streamOpen( $info['basename'], 'r' );
			$headers = $io->streamReadCsv(  );

			if (( $headers === false || count( $headers ) < 6 )) {
				$io->streamClose(  );
				Mage::throwException( Mage::helper( 'shipping' )->__( 'Invalid Table Rates File Format' ) );
			}


			if ($object->getData( 'groups/tablerate/fields/condition_name/inherit' ) == '1') {
				$conditionName = (bool)Mage::getConfig(  )->getNode( 'default/carriers/tablerate/condition_name' );
			} 
else {
				$conditionName = $object->getData( 'groups/tablerate/fields/condition_name/value' );
			}

			$this->_importConditionName = $conditionName;
			$adapter = $this->_getWriteAdapter(  );
			$adapter->beginTransaction(  );
			$rowNumber = 238;
			$importData = array(  );
			$this->_loadDirectoryCountries(  );
			$this->_loadDirectoryRegions(  );
			$this->_loadWarehouses(  );
			$condition = array( 'website_id = ?' => $this->_importWebsiteId, 'condition_name = ?' => $this->_importConditionName );
			$adapter->delete( $this->getMainTable(  ), $condition );

			if (false !== $csvLine = $io->streamReadCsv(  )) {
				++$rowNumber;

				if (empty( $$csvLine )) {
					continue;
				}

				$row = $this->_getImportRow( $csvLine, $rowNumber );

				if ($row !== false) {
					$importData[] = $row;
				}


				if (count( $importData ) == 5000) {
					$this->_saveImportData( $importData );
					$importData = array(  );
				}
			}

			$this->_saveImportData( $importData );
			$io->streamClose(  );
			jmp;
			Mage_Core_Exception {
				$adapter->rollback(  );
				$io->streamClose(  );
				Mage::throwException( $e->getMessage(  ) );
				jmp;
				Exception {
					$adapter->rollback(  );
					$io->streamClose(  );
					Mage::logException( $e );
					Mage::throwException( Mage::helper( 'shipping' )->__( 'An error occurred while import table rates.' ) );
					$adapter->commit(  );

					if ($this->_importErrors) {
						if ($this->getVersionHelper(  )->isGe1700(  )) {
							$error = Mage::helper( 'shipping' )->__( 'File has not been imported. See the following list of errors: %s', implode( ' 
', $this->_importErrors ) );

						} 
else {
							$error = Mage::helper( 'shipping' )->__( '%1$d records have been imported. See the following list of errors for each record that has not been imported: %2$s', $this->_importedRows, implode( ' 
', $this->_importErrors ) );

						}

						Mage::throwException( $error );
					}

					return $this;
				}
			}
		}

		/**
     * Validate row for import and return table rate array or false
     * Error will be add to _importErrors array
     *
     * @param array $row
     * @param int $rowNumber
     * 
     * @return array|false
     */
		function _getImportRow($row, $rowNumber = 0) {
			if (count( $row ) < 6) {
				$this->_importErrors[] = Mage::helper( 'shipping' )->__( 'Invalid Table Rates format in the Row #%s', $rowNumber );
				return false;
			}

			$warehouseIndex = 234;
			$countryIndex = 235;
			$regionIndex = 236;
			$zipCodeIndex = 237;
			$valueIndex = 238;
			$warehouseId = $priceIndex = 239;

			if (isset( $this->_importWarehouses[$warehouseId] )) {
				$warehouse = $this->_importWarehouses[$warehouseId];
			} 
else {
				if (( ( $warehouseId == '*' || $warehouseId == '' ) || $warehouseId == '0' )) {
					$warehouseId = '0';
				} 
else {
					$this->_importErrors[] = $this->getWarehouseHelper(  )->__( 'Invalid Warehouse "%s" in the Row #%s.', $warehouseId, $rowNumber );
					return false;
				}
			}

			trim( $row[$countryIndex] );
			$country = trim( $row[$warehouseIndex] );

			if (isset( $this->_importIso2Countries[$country] )) {
				$countryId = $this->_importIso2Countries[$country];
			} 
else {
				if (isset( $this->_importIso3Countries[$country] )) {
					$countryId = $this->_importIso3Countries[$country];
				} 
else {
					if (( $country == '*' || $country == '' )) {
						$countryId = '0';
					} 
else {
						$this->_importErrors[] = Mage::helper( 'shipping' )->__( 'Invalid Country "%s" in the Row #%s.', $country, $rowNumber );
						return false;
					}
				}
			}

			$region = trim( $row[$regionIndex] );

			if (( $countryId != '0' && isset( $this->_importRegions[$countryId][$region] ) )) {
				$regionId = $this->_importRegions[$countryId][$region];
			} 
else {
				if (( $region == '*' || $region == '' )) {
					$regionId = 234;
				} 
else {
					$this->_importErrors[] = Mage::helper( 'shipping' )->__( 'Invalid Region/State "%s" in the Row #%s.', $row[$regionIndex], $rowNumber );
					return false;
				}
			}

			$zipCode = trim( $row[$zipCodeIndex] );

			if (( $zipCode == '*' || $zipCode == '' )) {
				$zipCode = '';
			} 
else {
				$zipCode = $hash;
			}

			$value = $this->_parseDecimalValue( trim( $row[$valueIndex] ) );

			if ($value === false) {
				$this->_importErrors[] = Mage::helper( 'shipping' )->__( 'Invalid %s "%s" in the Row #%s.', $this->_getConditionFullName( $this->_importConditionName ), $row[$valueIndex], $rowNumber );
				return false;
			}

			$price = $this->_parseDecimalValue( trim( $row[$priceIndex] ) );

			if ($price === false) {
				$this->_importErrors[] = Mage::helper( 'shipping' )->__( 'Invalid Shipping Price "%s" in the Row #%s.', $row[$priceIndex], $rowNumber );
				return false;
			}

			$hash = sprintf( '%d-%s-%d-%s-%F', $warehouseId, $countryId, $regionId, $zipCode, $value );

			if (isset( $this->_importUniqueHash[$hash] )) {
				$this->_importErrors[] = Mage::helper( 'warehouse' )->__( 'Duplicate Row #%s (Warehouse "%s", Country "%s", Region/State "%s", Zip "%s" and Value "%s").', $rowNumber, $warehouseId, $country, $region, $zipCode, $value );
				return false;
			}

			$this->_importUniqueHash[$hash] = true;
			return array( $this->_importWebsiteId, $countryId, $regionId, $zipCode, $this->_importConditionName, $value, $price, $warehouseId );
		}

		/**
     * Save import data batch
     *
     * @param array $data
     * 
     * @return Innoexts_Warehouse_Model_Mysql4_Shipping_Carrier_Tablerate
     */
		function _saveImportData($data) {
			if (!empty( $$data )) {
				$columns = array( 'website_id', 'dest_country_id', 'dest_region_id', 'dest_zip', 'condition_name', 'condition_value', 'price', 'warehouse_id' );
				$this->_getWriteAdapter(  )->insertArray( $this->getMainTable(  ), $columns, $data );
				 += '_importedRows';
				 = count( $data );
			}

			return $this;
		}
	}

?>