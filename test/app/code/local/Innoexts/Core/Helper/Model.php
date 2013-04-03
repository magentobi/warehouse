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

	class Innoexts_Core_Helper_Model extends Mage_Core_Helper_Abstract {
		/**
     * Get core helper
     * 
     * @return Innoexts_Core_Helper_Data
     */
		function getCoreHelper() {
			return Mage::helper( 'innoexts_core' );
		}

		/**
     * Get version helper
     * 
     * @return Innoexts_Core_Helper_Version
     */
		function getVersionHelper() {
			return $this->getCoreHelper(  )->getVersionHelper(  );
		}

		/**
     * Cast value
     * 
     * @param mixed $value
     * @param string $type
     * 
     * @return mixed
     */
		function castValue($value, $type) {
			switch ($type) {
				case 'int': {
					$value = (int)$value;
					break;
				}

				case 'float': {
					$value = (double)$value;
					break;
				}

				case 'string': {
					$value = (bool)$value;
					break;
				}

				case 'array': {
					$value = (is_array( $value ) ? $value : array( $value ));
					break;
				}
			}

			$value = (bool)$value;
			break;
			return $value;
		}

		/**
     * Save child data
     * 
     * @param Mage_Core_Model_Abstract $model
     * @param string $modelClass
     * @param string $modelIdAttributeCode
     * @param string $dataTableName
     * @param string $dataAttributeCode
     * @param string $dataValueAttributeCode
     * @param string $dataValueType
     * 
     * @return Innoexts_Core_Helper_Model
     */
		function saveChildData($model, $modelClass, $modelIdAttributeCode, $dataTableName, $dataAttributeCode, $dataValueAttributeCode, $dataValueType = 'string') {
			if (( !$model || !( $model instanceof $modelClass ) )) {
				return $this;
			}

			$modelId = $model->getId(  );
			$resource = $model->getResource(  );
			$dataTable = $resource->getTable( $dataTableName );
			$adapter = $resource->getWriteConnection(  );
			$_data = $model->getData( $dataAttributeCode );

			if (!$_data) {
				$_data = array(  );
			}

			$data = array(  );
			$oldData = array(  );
			foreach ($_data as $value) {
				$value = $this->castValue( $value, $dataValueType );

				if (( $dataValueType == 'string' && !$value )) {
					continue;
				}

				$data[$value] = array( $modelIdAttributeCode => $modelId, $dataValueAttributeCode => $value );
			}

			$select = $adapter->select(  )->from( $dataTable )->where( $modelIdAttributeCode . ' = ?', $modelId );
			$query = $adapter->query( $select );

			if ($item = $query->fetch(  )) {
				$value = $item[$dataValueAttributeCode];
				$oldData[$value] = $item;
			}

			foreach ($oldData as $value => $item) {

				if (!isset( $data[$value] )) {
					$adapter->delete( $dataTable, array( $adapter->quoteInto( $modelIdAttributeCode . ' = ?', $modelId ), $adapter->quoteInto( $dataValueAttributeCode . ' = ?', $value ) ) );
					continue;
				}
			}

			foreach ($data as $value => $item) {

				if (!isset( $oldData[$value] )) {
					$adapter->insert( $dataTable, $item );
					continue;
				}
			}

			return $this;
		}

		/**
     * Save child data
     * 
     * @param Mage_Core_Model_Abstract $model
     * @param string $modelClass
     * @param string $modelIdAttributeCode
     * @param string $dataTableName
     * @param string $dataAttributeCode
     * @param string $dataKeyAttributeCode
     * @param string $dataValueAttributeCode
     * @param string $dataValueType
     * 
     * @return Innoexts_Core_Helper_Model
     */
		function saveChildData2($model, $modelClass, $modelIdAttributeCode, $dataTableName, $dataAttributeCode, $dataKeyAttributeCode, $dataValueAttributeCode, $dataValueType = 'string') {
			if (( !$model || !( $model instanceof $modelClass ) )) {
				return $this;
			}

			$modelId = $model->getId(  );
			$resource = $model->getResource(  );
			$dataTable = $resource->getTable( $dataTableName );
			$adapter = $resource->getWriteConnection(  );
			$_data = $model->getData( $dataAttributeCode );

			if (!$_data) {
				$_data = array(  );
			}


			if (count( $_data )) {
				$oldData = array(  );
				$adapter->select(  )->from( $dataTable )->where( $modelIdAttributeCode . ' = ?', $modelId );
				$select = $data = array(  );
				$adapter->query( $select );
				foreach ($_data as $query);

				if ($item = $query->fetch(  )) {
					$key = $item[$dataKeyAttributeCode];

					if ($dataValueType == 'array') {
						$value = $item[$dataValueAttributeCode];
						$oldData[$key][$value] = $item;
					}

					$oldData[$key] = $item;
				}


				if ($dataValueType == 'array') {
					foreach ($oldData as $key => $_data) {
						foreach ($_data as $value => $item) {

							if (!( isset( $data[$key] ) && isset( $data[$key][$value] ) )) {
								$adapter->delete( $dataTable, array( $adapter->quoteInto( $modelIdAttributeCode . ' = ?', $modelId ), $adapter->quoteInto( $dataKeyAttributeCode . ' = ?', $key ), $adapter->quoteInto( $dataValueAttributeCode . ' = ?', $value ) ) );
								continue;
							}
						}
					}
				} 
else {
					foreach ($oldData as $key => $item) {

						if (!isset( $data[$key] )) {
							$adapter->delete( $dataTable, array( $adapter->quoteInto( $modelIdAttributeCode . ' = ?', $modelId ), $adapter->quoteInto( $dataKeyAttributeCode . ' = ?', $key ) ) );
							continue;
						}
					}
				}


				if ($dataValueType == 'array') {
					foreach ($data as $key => $_data) {
						foreach ($_data as $value => $item) {

							if (!( isset( $oldData[$key] ) && isset( $oldData[$key][$value] ) )) {
								$adapter->insert( $dataTable, $item );
								continue;
							}
						}
					}
				} 
else {
					foreach ($data as $key => $item) {

						if (!isset( $oldData[$key] )) {
							$adapter->insert( $dataTable, $item );
							continue;
						}

						$adapter->update( $dataTable, $item, array( $adapter->quoteInto( $modelIdAttributeCode . ' = ?', $modelId ), $adapter->quoteInto( $dataKeyAttributeCode . ' = ?', $key ) ) );
					}
				}
			}

			return $this;
		}

		/**
     * Add child data
     * 
     * @param Mage_Core_Model_Abstract $model
     * @param string $modelClass
     * @param array $data
     * @param string $dataAttributeCode
     * 
     * @return Innoexts_Core_Helper_Model
     */
		function addChildData($model, $modelClass, $array, $dataAttributeCode) {
			if (( !$model || !( $model instanceof $modelClass ) )) {
				return $this;
			}


			if (( !isset( $array[$dataAttributeCode] ) || !$array[$dataAttributeCode] )) {
				$model->setData( $dataAttributeCode, array(  ) );
			}

			return $this;
		}

		/**
     * Load child data
     * 
     * @param Mage_Core_Model_Abstract $model
     * @param string $modelClass
     * @param string $modelIdAttributeCode
     * @param string $dataTableName
     * @param string $dataAttributeCode
     * @param string $dataValueAttributeCode
     * @param string $dataValueType
     * 
     * @return Innoexts_Core_Helper_Model
     */
		function loadChildData($model, $modelClass, $modelIdAttributeCode, $dataTableName, $dataAttributeCode, $dataValueAttributeCode, $dataValueType = 'string') {
			if (( ( !$model || !( $model instanceof $modelClass ) ) || $model->hasData( $dataAttributeCode ) )) {
				return $this;
			}

			$model->getResource(  );
			$resource->getTable( $dataTableName );
			$adapter = $dataTable = $resource = $resource->getWriteConnection(  );
			$adapter->select(  )->from( $dataTable )->where( $modelIdAttributeCode . ' = ?', $model->getId(  ) );
			$query = $select = $adapter->query( $select );
			$query->fetch(  );

			if ($item = $data = array(  )) {
				$value = $item[$dataValueAttributeCode];

				if ($dataValueType == 'string') {
					$data[] = $value;
				}

				$data[$value] = $value;
			}

			$model->setData( $dataAttributeCode, $data );
			return $this;
		}

		/**
     * Load child data
     * 
     * @param Mage_Core_Model_Abstract $model
     * @param string $modelClass
     * @param string $modelIdAttributeCode
     * @param string $dataTableName
     * @param string $dataAttributeCode
     * @param string $dataKeyAttributeCode
     * @param string $dataValueAttributeCode
     * @param string $dataValueType
     * 
     * @return Innoexts_Core_Helper_Model
     */
		function loadChildData2($model, $modelClass, $modelIdAttributeCode, $dataTableName, $dataAttributeCode, $dataKeyAttributeCode, $dataValueAttributeCode, $dataValueType = 'string') {
			if (( ( !$model || !( $model instanceof $modelClass ) ) || $model->hasData( $dataAttributeCode ) )) {
				return $this;
			}

			$resource = $model->getResource(  );
			$dataTable = $resource->getTable( $dataTableName );
			$adapter = $resource->getWriteConnection(  );
			$select = $adapter->select(  )->from( $dataTable )->where( $modelIdAttributeCode . ' = ?', $model->getId(  ) );
			$query = $adapter->query( $select );
			$data = array(  );

			if ($item = $query->fetch(  )) {
				$key = $item[$dataKeyAttributeCode];
				$value = $item[$dataValueAttributeCode];

				if ($dataValueType == 'array') {
					$data[$key][$value] = $value;
				}

				$data[$key] = $value;
			}

			$model->setData( $dataAttributeCode, $data );
			return $this;
		}

		/**
     * Load collection child data
     * 
     * @param Varien_Data_Collection_Db $collection
     * @param string $modelIdAttributeCode
     * @param string $dataTableName
     * @param string $dataAttributeCode
     * @param string $dataValueAttributeCode
     * @param string $dataValueType
     * 
     * @return Innoexts_Core_Helper_Model
     */
		function loadCollectionChildData($collection, $modelIdAttributeCode, $dataTableName, $dataAttributeCode, $dataValueAttributeCode, $dataValueType = 'string') {
			if (!$collection) {
				return $this;
			}

			$modelIds = array(  );
			foreach ($collection as $model) {
				array_push( $modelIds, $model->getId(  ) );
			}


			if (!count( $modelIds )) {
				return $this;
			}

			$dataTable = $collection->getTable( $dataTableName );
			$adapter = $collection->getConnection(  );
			$select = $adapter->select(  )->from( $dataTable )->where( $adapter->quoteInto( $modelIdAttributeCode . ' IN (?)', $modelIds ) );
			$query = $adapter->query( $select );
			$modelData = array(  );

			if ($item = $query->fetch(  )) {
				$modelId = $item[$modelIdAttributeCode];
				$value = $item[$dataValueAttributeCode];

				if ($dataValueType == 'string') {
					$modelData[$modelId][] = $value;
				}

				$modelData[$modelId][$value] = $value;
			}

			foreach ($collection as $model) {
				$modelId = $model->getId(  );
				$data = array(  );

				if (isset( $modelData[$modelId] )) {
					$data = $modelData[$modelId];
				}

				$model->setData( $dataAttributeCode, $data );
			}

			return $this;
		}

		/**
     * Load collection child data
     * 
     * @param Varien_Data_Collection_Db $collection
     * @param string $modelIdAttributeCode
     * @param string $dataTableName
     * @param string $dataAttributeCode
     * @param string $dataKeyAttributeCode
     * @param string $dataValueAttributeCode
     * @param string $dataValueType
     * 
     * @return Innoexts_Core_Helper_Model
     */
		function loadCollectionChildData2($collection, $modelIdAttributeCode, $dataTableName, $dataAttributeCode, $dataKeyAttributeCode, $dataValueAttributeCode, $dataValueType = 'string') {
			if (!$collection) {
				return $this;
			}

			$modelIds = array(  );
			foreach ($collection as $model) {
				array_push( $modelIds, $model->getId(  ) );
			}


			if (count( $modelIds )) {
				$collection->getTable( $dataTableName );
				$adapter = $collection->getConnection(  );
				$select = $adapter->select(  )->from( $dataTable )->where( $adapter->quoteInto( $modelIdAttributeCode . ' IN (?)', $modelIds ) );
				$query = $adapter->query( $select );
				$modelData = array(  );

				if ($item = $dataTable = $query->fetch(  )) {
					$modelId = $item[$modelIdAttributeCode];
					$key = $item[$dataKeyAttributeCode];
					$value = $item[$dataValueAttributeCode];

					if ($dataValueType == 'array') {
						$modelData[$modelId][$key][$value] = $value;
					}

					$modelData[$modelId][$key] = $value;
				}

				foreach ($collection as $model) {
					$modelId = $model->getId(  );
					$data = array(  );

					if (isset( $modelData[$modelId] )) {
						$data = $modelData[$modelId];
					}

					$model->setData( $dataAttributeCode, $data );
				}
			}

			return $this;
		}

		/**
     * Remove child data
     * 
     * @param Mage_Core_Model_Abstract $model
     * @param string $modelClass
     * @param string $dataAttributeCode
     * 
     * @return Innoexts_Core_Helper_Model
     */
		function removeChildData($model, $modelClass, $dataAttributeCode) {
			if (( !$model || !( $model instanceof $modelClass ) )) {
				return $this;
			}

			$model->unsetData( $dataAttributeCode );
			return $this;
		}
	}

?>