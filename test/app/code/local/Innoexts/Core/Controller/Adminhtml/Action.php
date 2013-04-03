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

	class Innoexts_Core_Controller_Adminhtml_Action extends Mage_Adminhtml_Controller_Action {
		private $_modelNames = array(  );

		/**
     * Retrieve admin session
     *
     * @return Mage_Admin_Model_Session
     */
		function getAdminSession() {
			return Mage::getSingleton( 'admin/session' );
		}

		/**
     * Get model name by type
     * 
     * @param string $type
     * 
     * @return string
     */
		function _getModelNameByType($type) {
			if (isset( $this->_modelNames[$type] )) {
				return $this->_modelNames[$type];
			}

		}

		/**
     * Get model
     * 
     * @param string $type
     * 
     * @return Mage_Core_Model_Abstract
     */
		function _getModel($type) {
			return Mage::getModel( $this->_getModelNameByType( $type ) );
		}

		/**
     * Initialize model
     * 
     * @param string $type
     * @param bool $isAjax
     * @param string $idParamName
     * @param string $indexActionName
     * @param string $notFoundMessage
     * 
     * @return Innoexts_Core_Controller_Adminhtml_Action
     */
		function _initModel($type, $isAjax, $idParamName, $indexActionName, $notFoundMessage) {
			$model = $this->_getModel( $type );
			$id = (int)$this->getRequest(  )->getParam( $idParamName );

			if ($id) {
				$model->load( $id );

				if (!$model->getId(  )) {
					$this->_getSession(  )->addError( $notFoundMessage );

					if (!$isAjax) {
						$this->_redirect( '*/*/' . $indexActionName );
					}

					return null;
				}
			}

			Mage::register( $type, $model );
			return $this;
		}

		/**
     * Edit action
     * 
     * @param string $type
     * @param bool $isAjax
     * @param string $menu
     * @param string $idParamName
     * @param string $indexActionName
     * @param string $newMessage
     * @param string $editMessage
     * @param array $breadcrumb
     * @param string $notFoundMessage
     * 
     * @return Innoexts_Core_Controller_Adminhtml_Action
     */
		function _editAction($type, $isAjax, $menu, $idParamName, $indexActionName, $newMessage, $editMessage, $breadcrumb = array(  ), $notFoundMessage) {
			$adminhtmlSession = $this->_getSession(  );
			$request = $this->getRequest(  );
			$model = $this->_getModel( $type );
			$id = $request->getParam( $idParamName );
			$error = false;

			if ($id) {
				$model->load( $id );

				if (!$model->getId(  )) {
					$error = true;
					$adminhtmlSession->addError( $notFoundMessage );
				}
			}


			if (!$isAjax) {
				if ($error) {
					$this->_redirect( '*/*/' . $indexActionName );
				}

				$data = $adminhtmlSession->getFormData( true );

				if (!empty( $$data )) {
					$model->setData( $data );
				}

				Mage::register( $type, $model );

				if (count( $breadcrumb )) {
					foreach ($breadcrumb as $label) {
						$this->_title( $label );
					}
				}

				$this->_title( ($model->getId(  ) ? $model->getTitle(  ) : $newMessage) );
				$title = ($model->getId(  ) ? $editMessage : $newMessage);
				$this->loadLayout(  )->_setActiveMenu( $menu );
				$this->_addBreadcrumb( $title, $title );
				$this->renderLayout(  );
			} 
else {
				$data = $model->getData(  );
				$data['title'] = $model->getTitle(  );
				$this->_initLayoutMessages( 'adminhtml/session' );
				$this->getResponse(  )->setBody( Mage::helper( 'core' )->jsonEncode( array( 'error' => ($error ? 1 : 0), 'messages' => $this->getLayout(  )->getMessagesBlock(  )->getGroupedHtml(  ), 'data' => $data ) ) );
			}

			return $this;
		}

		/**
     * Prepare save
     * 
     * @param string $type
     * @param Mage_Core_Model_Abstract $model
     * 
     * @return Innoexts_Core_Controller_Adminhtml_Action
     */
		function _prepareSave($type, $model) {
			return $this;
		}

		/**
     * Save action
     * 
     * @param string $type
     * @param bool $isAjax
     * @param string $idParamName
     * @param string $indexActionName
     * @param string $editActionName
     * @param string $savedMessage
     * @param string $errorMessage
     * 
     * @return Innoexts_Core_Controller_Adminhtml_Action
     */
		function _saveAction($type, $isAjax, $idParamName, $indexActionName, $editActionName, $savedMessage, $errorMessage) {
			$adminhtmlSession = $this->_getSession(  );
			$request = $this->getRequest(  );
			$model = $this->_getModel( $type );
			$data = $request->getPost( $type );
			$error = false;

			if (( isset( $data[$idParamName] ) && !empty( $data[$idParamName] ) )) {
				$id = $data[$idParamName];
			} 
else {
				$id = null;
			}


			if ($id) {
				$model->load( $id );
			}

			$model->addData( $data );

			if (!$model->getId(  )) {
				$model->setId( null );
			}

			$this->_prepareSave( $type, $model );
			Mage::dispatchEvent( $type . '_prepare_save', array( 'model' => $model, 'request' => $request ) );
			$model->save(  );
			$adminhtmlSession->addSuccess( $savedMessage );

			if (!$isAjax) {
				$adminhtmlSession->setFormData( false );
                try {
    				if ($request->getParam( 'back' )) {
    					$this->_redirect( '*/*/' . $editActionName, array( $idParamName => $model->getId(  ), '_current' => true ) );
    					return null;
    				}
    
    				$this->_redirect( '*/*/' . $indexActionName );
    				return $this;
                } catch (Mage_Core_Exception $e) {
					$error = 348;
					$adminhtmlSession->addError( $e->getMessage(  ) );
				}
			}


			if ($isAjax) {
				$this->_initLayoutMessages( 'adminhtml/session' );
				$this->getResponse(  )->setBody( Mage::helper( 'core' )->jsonEncode( array( 'error' => ($error ? 1 : 0), 'messages' => $this->getLayout(  )->getMessagesBlock(  )->getGroupedHtml(  ) ) ) );
			} 
else {
				$adminhtmlSession->setFormData( $data );
				$this->_redirect( '*/*/' . $editActionName, array( $idParamName => $id ) );
				return $this;
			}

			return $this;
		}

		/**
     * Delete action
     * 
     * @param string $type
     * @param bool $isAjax
     * @param string $idParamName
     * @param string $indexActionName
     * @param string $editActionName
     * @param string $notFoundMessage
     * @param string $deletedMessage
     * 
     * @return Innoexts_Core_Controller_Adminhtml_Action
     */
		function _deleteAction($type, $isAjax, $idParamName, $indexActionName, $editActionName, $notFoundMessage, $deletedMessage) {
			$adminhtmlSession = $this->_getSession(  );
			$request = $this->getRequest(  );
			$model = $this->_getModel( $type );
			$id = $request->getParam( $idParamName );
			$error = false;

			if ($id) {
				$model->load( $id );
			}


			if ($model->getId(  )) {
				$title = $model->getTitle(  );
				$model->delete(  );
				$adminhtmlSession->addSuccess( $deletedMessage );
				Mage::dispatchEvent( $type . '_on_delete', array( 'title' => $title, 'status' => 'success' ) );
			}

			$error = true;
			Mage::dispatchEvent( $type . '_on_delete', array( 'title' => '', 'status' => 'fail' ) );
			$adminhtmlSession->addError( $notFoundMessage );

			if ($isAjax) {
				$this->_initLayoutMessages( 'adminhtml/session' );
				$this->getResponse(  )->setBody( Mage::helper( 'core' )->jsonEncode( array( 'error' => ($error ? 1 : 0), 'messages' => $this->getLayout(  )->getMessagesBlock(  )->getGroupedHtml(  ) ) ) );
			} 
else {
				$this->_redirect( '*/*/' . $indexActionName );
			}

			return $this;
		}

		/**
     * Mass delete action
     * 
     * @param string $type
     * @param bool $isAjax
     * @param string $idParamName
     * @param string $indexActionName
     * @param string $selectMessage
     * @param string $deletedMessage
     * 
     * @return Innoexts_Core_Controller_Adminhtml_Action
     */
		function _massDeleteAction($type, $isAjax, $idParamName, $indexActionName, $selectMessage, $deletedMessage) {
			$adminhtmlSession = $this->_getSession(  );
			$request = $this->getRequest(  );
			$error = false;
			$ids = $request->getParam( $idParamName );

			if (( is_array( $ids ) && !empty( $$ids ) )) {
				foreach ($ids as $id) {
					$model = $this->_getModel( $type );
					$model->load( $id );
					$title = $model->getTitle(  );
					$model->delete(  );
					Mage::dispatchEvent( $type . '_on_delete', array( 'title' => $title, 'status' => 'success' ) );
				}

				$adminhtmlSession->addSuccess( sprintf( $deletedMessage, count( $ids ) ) );
			}

			$error = true;
			$adminhtmlSession->addError( $selectMessage );
			Mage::dispatchEvent( $type . '_on_delete', array( 'title' => '', 'status' => 'fail' ) );

			if ($isAjax) {
				$this->_initLayoutMessages( 'adminhtml/session' );
				$this->getResponse(  )->setBody( Mage::helper( 'core' )->jsonEncode( array( 'error' => ($error ? 1 : 0), 'messages' => $this->getLayout(  )->getMessagesBlock(  )->getGroupedHtml(  ) ) ) );
			} 
else {
				$this->_redirect( '*/*/' . $indexActionName );
			}

			return $this;
		}

		/**
     * Grid action
     * 
     * @param string $type
     * @param bool $isAjax
     * 
     * @return Innoexts_Core_Controller_Adminhtml_Action
     */
		function _gridAction($type, $isAjax) {
			if ($isAjax) {
				$this->loadLayout(  );
				$this->renderLayout(  );
			}

			return $this;
		}

		/**
     * Index action
     * 
     * @param string $type
     * @param bool $isAjax
     * @param string $menu
     * @param array $breadcrumb
     * 
     * @return Innoexts_Core_Controller_Adminhtml_Action
     */
		function _indexAction($type, $isAjax, $menu, $breadcrumb = array(  )) {
			if (!$isAjax) {
				$this->loadLayout(  );
				$this->_setActiveMenu( $menu );

				if (count( $breadcrumb )) {
					foreach ($breadcrumb as $label) {
						$this->_title( $label );
						$this->_addBreadcrumb( $label, $label );
					}
				}

				$this->renderLayout(  );
			}

			return $this;
		}

		/**
     * Regions action
     * 
     * @return Innoexts_Core_Controller_Adminhtml_Action
     */
		function regionsAction() {
			$arrRes = array(  );
			$countryId = $this->getRequest(  )->getParam( 'parent' );
			$arrRegions = Mage::getResourceModel( 'directory/region_collection' )->addCountryFilter( $countryId )->load(  )->toOptionArray(  );

			if (!empty( $$arrRegions )) {
				foreach ($arrRegions as $region) {
					$arrRes[] = $region;
				}
			}

			$this->getResponse(  )->setBody( Mage::helper( 'core' )->jsonEncode( $arrRes ) );
			return $this;
		}

		/**
     * Export CSV action
     * 
     * @param string $fileName
     * @param string $blockType
     * 
     * @return Innoexts_Core_Controller_Adminhtml_Action
     */
		function _exportCsvAction($fileName, $blockType) {
			$this->_prepareDownloadResponse( $fileName, $this->getLayout(  )->createBlock( $blockType )->getCsvFile(  ) );
			return $this;
		}

		/**
     * Export XML action
     * 
     * @param string $fileName
     * @param string $blockType
     * 
     * @return Innoexts_Core_Controller_Adminhtml_Action
     */
		function _exportXmlAction($fileName, $blockType) {
			$this->_prepareDownloadResponse( $fileName, $this->getLayout(  )->createBlock( $blockType )->getExcelFile(  ) );
			return $this;
		}
	}

?>