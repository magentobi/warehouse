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

	class Innoexts_Core_Helper_Database extends Mage_Core_Helper_Abstract {
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
     * Replace unique key
     * 
     * @param Mage_Core_Model_Resource_Setup $setup
     * @param string $tableName
     * @param string $keyName
     * @param array $keyAttributes
     * 
     * @return Innoexts_Core_Helper_Database
     */
		function replaceUniqueKey($setup, $tableName, $keyName, $keyAttributes) {
			$connection = $setup->getConnection(  );
			$versionHelper = $this->getVersionHelper(  );
			$table = $setup->getTable( $tableName );

			if ($versionHelper->isGe1600(  )) {
				$indexTypeUnique = INDEX_TYPE_UNIQUE;
				$indexes = $connection->getIndexList( $table );
				foreach ($indexes as $index) {

					if ($index['INDEX_TYPE'] == $indexTypeUnique) {
						$connection->dropIndex( $table, $index['KEY_NAME'] );
						continue;
					}
				}

				$keyName = $setup->getIdxName( $tableName, $keyAttributes, $indexTypeUnique );
				$connection->addIndex( $table, $keyName, $keyAttributes, $indexTypeUnique );
			} 
else {
				$connection->addKey( $table, $keyName, $keyAttributes, 'unique' );
			}

			return $this;
		}

		/**
     * Get table
     * 
     * @param string $entityName
     * 
     * @return string 
     */
		function getTable($entityName) {
			return Mage::getSingleton( 'core/resource' )->getTableName( $entityName );
		}
	}

?>