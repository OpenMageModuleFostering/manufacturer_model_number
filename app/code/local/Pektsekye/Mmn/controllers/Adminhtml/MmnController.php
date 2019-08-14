<?php

class Pektsekye_Mmn_Adminhtml_MmnController extends Mage_Adminhtml_Controller_action
{

	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('mmn/items')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
		
		return $this;
	}   
 
	public function indexAction() {
		$this->_initAction()
			->renderLayout();
	}

	public function editAction() {
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('mmn/mmn')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('mmn_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('mmn/items');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('mmn/adminhtml_mmn_edit'))
				->_addLeft($this->getLayout()->createBlock('mmn/adminhtml_mmn_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('mmn')->__('Item does not exist'));
			$this->_redirect('*/*/');
		}
	}
 
	public function newAction() {
		$this->_forward('edit');
	}
 
	public function saveAction() {
		if ($data = $this->getRequest()->getPost()) {
			
			$data ['entity_id'] = (int) $data ['entity_id'];
			$data ['manufacturer'] = trim(preg_replace('/[^\w\s-]/','',$data ['manufacturer']));
			$data ['model'] = trim(preg_replace('/[^\w\s-]/','',$data ['model']));
			$data ['number'] = trim(preg_replace('/[^\w\s-]/','',$data ['number']));

			$model = Mage::getModel('mmn/mmn');		
			$model->setData($data)
				->setId($this->getRequest()->getParam('id'));
			
			try {
				
				$model->save();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('mmn')->__('Item was successfully saved'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);

				if ($this->getRequest()->getParam('back')) {
					$this->_redirect('*/*/edit', array('id' => $model->getId()));
					return;
				}
				$this->_redirect('*/*/');
				return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('mmn')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
	}
 
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('mmn/mmn');
				 
				$model->setId($this->getRequest()->getParam('id'))
					->delete();
					 
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}

    public function massDeleteAction() {
        $mmnIds = $this->getRequest()->getParam('mmn');
        if(!is_array($mmnIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($mmnIds as $mmnId) {
                    $mmn = Mage::getModel('mmn/mmn')->load($mmnId);
                    $mmn->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($mmnIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
	
    public function massStatusAction()
    {
        $mmnIds = $this->getRequest()->getParam('mmn');
        if(!is_array($mmnIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($mmnIds as $mmnId) {
                    $mmn = Mage::getSingleton('mmn/mmn')
                        ->load($mmnId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($mmnIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
  

    protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream')
    {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK','');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename='.$fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }
	

    /**
     * Import and export Page
     *
     */
    public function importExportAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('mmn/import')
            ->_addContent($this->getLayout()->createBlock('mmn/adminhtml_mmn_importExport'))
            ->renderLayout();
    }

    /**
     * import action from import/export mmn
     *
     */
    public function importPostAction()
    {
        if ($this->getRequest()->isPost() && !empty($_FILES['import_mmn_file']['tmp_name'])) {
            try {
                $number = $this->_importMmn();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('mmn')->__('%d new item(s) were imported',$number));
            }
            catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('mmn')->__('Invalid file upload attempt'));
            }
        }
        else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('mmn')->__('Invalid file upload attempt'));
        }
        $this->_redirect('*/*/importExport');
    }

    protected function _importMmn()
    {
        $fileName   = $_FILES['import_mmn_file']['tmp_name'];
        $csvObject  = new Varien_File_Csv();
        $csvData = $csvObject->getData($fileName);
		$number = 0;
        /** checks columns */
        $csvFields  = array(
            0   => Mage::helper('mmn')->__('Products ID'),
            1   => Mage::helper('mmn')->__('Printer Manufacturer'),
            2   => Mage::helper('mmn')->__('Printer Model'),
            3   => Mage::helper('mmn')->__('Printer Number')
        );

        if ($csvData[0] == $csvFields) {
            foreach ($csvData as $k => $v) {
                if ($k == 0) {
                    continue;
                }

                //end of file has more then one empty lines
                if (count($v) <= 1 && !strlen($v[0])) {
                    continue;
                }

                if (count($csvFields) != count($v)) {
                    Mage::getSingleton('adminhtml/session')->addError(Mage::helper('mmn')->__('Invalid file upload attempt'));
                }
				
				if (!empty($v[0]) && is_numeric($v[0]) && !empty($v[1])) {
					
				    $v[1] = trim(preg_replace('/[^\w\s-]/','',$v[1]));
					$v[2] = trim(preg_replace('/[^\w\s-]/','',$v[2]));
					$v[3] = trim(preg_replace('/[^\w\s-]/','',$v[3]));
					
					$resource = Mage::getSingleton('core/resource');
					$read= $resource->getConnection('core_read');
					$mmnTable = $resource->getTableName('mmn');
					$select = $read->select()
											->from($mmnTable,array('mmn_id'))
											->where("entity_id=?",(int)$v[0])
											->where("manufacturer=?",$v[1])
											->where("model=?",$v[2])
											->where("number=?",$v[3])										
											->limit(1);
											
					if($read->fetchOne($select)){
						 continue;
					}	 

					$data  = array(
						'entity_id'=>$v[0],
						'manufacturer' => $v[1],
						'model' => $v[2],
						'number'  => $v[3]
					);

					$model  = Mage::getModel('mmn/mmn');
					$model->setData($data);
					$model->save();
					$number++;
                }
            }  
        }
        else {
            Mage::throwException(Mage::helper('mmn')->__('Invalid file format upload attempt'));
        }
		
		return $number;
    }

    /**
     * export action from import/export tax
     *
     */
    public function exportPostAction()
    {
        $fileName   = 'mmn.csv';
        $content    = $this->getLayout()->createBlock('mmn/adminhtml_mmn_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }
	
}