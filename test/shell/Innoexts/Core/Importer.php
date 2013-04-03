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

	require_once( rtrim( dirname( __FILE__ ), '/' ) . '/../../abstract.php' );
	class Innoexts_Shell_Core_Importer extends Mage_Shell_Abstract {
		private $_nl = '
';
		private $_tab = '	';
		private $_ftpConfig = array( 'host' => 'ftp.yourhost.com', 'user' => 'username', 'password' => 'password', 'filename' => 'remotefilename' );
		private $_fileConfig = array( 'path' => '/var/import/', 'filename' => 'localfilename', 'delimiter' => ',', 'enclosure' => '"' );
		private $_ftp = null;
		private $_file = null;

		/**
     * Get new line
     * 
     * @return string
     */
		function getNl() {
			return $this->_nl;
		}

		/**
     * Get tab
     * 
     * @return string
     */
		function getTab() {
			return $this->_tab;
		}

		/**
     * Get FTP config
     * 
     * @return array
     */
		function getFTPConfig() {
			return $this->_ftpConfig;
		}

		/**
     * Get File config
     * 
     * @return array
     */
		function getFileConfig() {
			return $this->_fileConfig;
		}

		/**
     * Get FTP filename
     * 
     * @return string
     */
		function getFTPFilename() {
			$config = $this->getFTPConfig(  );
			return (isset( $config['filename'] ) ? $config['filename'] : null);
		}

		/**
     * Get file name
     * 
     * @return string
     */
		function getFileFilename() {
			$config = $this->getFileConfig(  );
			return (isset( $config['filename'] ) ? $config['filename'] : null);
		}

		/**
     * Get file path
     * 
     * @return string
     */
		function getFilePath() {
			$config = $this->getFileConfig(  );
			return Mage::getBaseDir(  ) . DS . trim( $config['path'], DS ) . DS . $config['filename'];
		}

		/**
     * Print message
     * 
     * @param string    $message
     * @param bool      $newLine 
     * @param int|null  $tabs
     * 
     * @return Innoexts_Shell_Core_Importer
     */
		function printMessage($message, $newLine = true, $tabs = null) {
			if (!is_null( $tabs )) {
				$message = str_pad( $this->getTab(  ), (int)$tabs ) . $message;
			}


			if ($newLine) {
				$message .= $this->getNl(  );
			}

			echo $message;
			return $this;
		}

		/**
     * Get file
     * 
     * @return Varien_Io_File
     */
		function getFile() {
			while (is_null( $this->_file )) {
				$file = new Varien_Io_File(  );
				$config = $this->getFileConfig(  );
				$path = $file->getCleanPath( Mage::getBaseDir(  ) . DS . trim( $config['path'], DS ) );
				$file->checkAndCreateFolder( $path );
				$config['path'] = rtrim( realpath( $path ), DS );
				$file->open( $config );
				$this->_file = $file;
				break;
			}

			return $this->_file;
		}

		/**
     * Get FTP
     * 
     * @return Varien_Io_Ftp
     */
		function getFTP() {
			while (is_null( $this->_ftp )) {
				$ftp = new Varien_Io_Ftp(  );
				$config = $this->getFTPConfig(  );
				$ftp->open( $config );
				$this->_ftp = $ftp;
				break;
			}

			return $this->_ftp;
		}

		/**
     * Download file
     * 
     * @return bool
     */
		function download() {
			$isDownloaded = false;
			$this->printMessage( 'Downloading data file...' );
			$ftp = $this->getFTP(  );

			if (!is_null( $ftp )) {
				$file = $this->getFile(  );

				if (!is_null( $file )) {
					$data = $ftp->read( $this->getFTPFilename(  ), $this->getFilePath(  ) );

					if (false !== $data) {
						$isDownloaded = true;
						$this->printMessage( 'Downloaded.' );
					} 
else {
						$this->printMessage( 'Could not download file: ' . $this->getFTPFilename(  ) );
					}
				}
			}

			return $isDownloaded;
		}

		/**
     * Parse FTP arguments
     * 
     * @return bool
     */
		function parseFTPArgs() {
			$isParsed = true;

			if ($this->getArg( 'ftp' )) {
				$ftpHost = trim( $this->getArg( 'ftp-host' ) );

				if (!$ftpHost) {
					$this->printMessage( 'FTP host is required.' );
					$isParsed = false;
				} 
else {
					$this->_ftpConfig['host'] = $ftpHost;
				}

				$ftpUser = trim( $this->getArg( 'ftp-user' ) );

				if (!$ftpUser) {
					$this->printMessage( 'FTP user is required.' );
					$isParsed = false;
				} 
else {
					$this->_ftpConfig['user'] = $ftpUser;
				}

				$ftpPassword = trim( $this->getArg( 'ftp-password' ) );

				if (!$ftpPassword) {
					$this->printMessage( 'FTP password is required.' );
					$isParsed = false;
				} 
else {
					$this->_ftpConfig['password'] = $ftpPassword;
				}

				$ftpFilename = trim( $this->getArg( 'ftp-filename' ) );

				if (!$ftpFilename) {
					$this->printMessage( 'FTP filename is required.' );
					$isParsed = false;
				} 
else {
					$this->_ftpConfig['filename'] = $ftpFilename;
				}
			}

			return $isParsed;
		}

		/**
     * Parse file arguments
     * 
     * @return bool
     */
		function parseFileArgs() {
			$isParsed = true;
			$filePath = trim( $this->getArg( 'file-path' ) );

			if (!$filePath) {
				$this->printMessage( 'File path is required.' );
				$isParsed = false;
			} 
else {
				$this->_fileConfig['path'] = $filePath;
			}

			$fileFilename = trim( $this->getArg( 'file-filename' ) );

			if (!$fileFilename) {
				$this->printMessage( 'File filename is required.' );
				$isParsed = false;
			} 
else {
				$this->_fileConfig['filename'] = $fileFilename;
			}

			$fileCsvDelimiter = trim( $this->getArg( 'file-csv-delimiter' ) );

			if ($fileCsvDelimiter) {
				$this->_fileConfig['delimiter'] = $fileCsvDelimiter;
			}

			$fileCsvEnclosure = trim( $this->getArg( 'file-csv-enclosure' ) );

			if ($fileCsvEnclosure) {
				$this->_fileConfig['enclosure'] = $fileCsvEnclosure;
			}

			return $isParsed;
		}

		/**
     * Parse arguments
     * 
     * @return bool
     */
		function parseArgs() {
			return ( $this->parseFTPArgs(  ) && $this->parseFileArgs(  ) );
		}

		/**
     * Reindex
     * 
     * @return Innoexts_Shell_Core_Importer
     */
		function reindex() {
			$this->printMessage( 'Reindexing.' );
			return $this;
		}

		/**
     * Import prices
     * 
     * @return bool
     */
		function import() {
			$isImported = false;

			if (!$this->parseArgs(  )) {
				return $this;
			}


			if ($this->getArg( 'ftp' )) {
				$this->download(  );
			}

			$this->printMessage( 'Importing data file...' );
			$file = $this->getFile(  );

			if ($file) {
				$config = $this->getFileConfig(  );
				$file->streamOpen( $this->getFileFilename(  ), 'r' );
				$_fieldNames = $file->streamReadCsv( $config['delimiter'], $config['enclosure'] );

				if (count( $_fieldNames )) {
					$fieldNames = array(  );
					foreach ($_fieldNames as $index => $fieldName) {
						$fieldNames[$index] = trim( strtolower( $fieldName ) );
					}

					$file->streamReadCsv( $config['delimiter'], $config['enclosure'] );

					if ($csvData !== false) {
						if (( count( $csvData ) == 1 && $csvData[0] === null )) {
							continue;
						}

						$row = array(  );
						foreach ($fieldNames as $index => $fieldName) {

							if (isset( $csvData[$index] )) {
								$row[$fieldName] = $csvData[$index];
								continue;
							}
						}

						$this->importRow( $row );
					}

					$isImported = true;
					$this->reindex(  );
					$this->printMessage( 'Imported.' );
				}

				$this->printMessage( 'Data file header was not found.' );
			}

			return $isImported;
		}

		/**
     * Run script
     */
		function run() {
			if (!$this->getArg( 'help' )) {
				$this->import(  );
				return null;
			}

			$this->printHelp(  );
		}

		/**
     * Print help
     * 
     * @return Innoexts_Shell_Core_Importer
     */
		function printHelp() {
			$this->printMessage( $this->usageHelp(  ) );
			return $this;
		}

		/**
     * Get help message
     */
		function usageHelp() {
			return 'Usage:  php -f Importer.php -- [options]
  
  ftp <flag>                                    Check if data file should be downloaded from the FTP server first
  ftp-host <ftp-host>                           FTP host
  ftp-user <ftp-user>                           FTP user
  ftp-password <ftp-password>                   FTP password
  ftp-filename <ftp-filename>                   FTP filename
  
  file-path <file-path>                         File path
  file-filename <file-filename>                 File filename
  file-csv-delimiter <file-csv-delimiter>       File CSV delimiter
  file-csv-enclosure <file-csv-enclosure>       File CSV enclosure
  
  help                                          This help';
		}
	}

?>