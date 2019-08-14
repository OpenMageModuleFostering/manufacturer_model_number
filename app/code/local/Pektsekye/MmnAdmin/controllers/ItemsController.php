<?php

class Pektsekye_MmnAdmin_ItemsController extends Mage_Adminhtml_Controller_action
{

	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('mmnadmin/items')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
		
		return $this;
	}   
 
	public function indexAction() {
		$this->_initAction()
			->renderLayout();
	}

	public function editAction() {
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('mmnadmin/mmnAdmin')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('mmnadmin_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('mmnadmin/items');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('mmnadmin/items_edit'))
				->_addLeft($this->getLayout()->createBlock('mmnadmin/items_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('mmnadmin')->__('Item does not exist'));
			$this->_redirect('*/*/');
		}
	}
 
	public function newAction() {
		$this->_forward('edit');
	}
 
	public function saveAction() {
		if ($data = $this->getRequest()->getPost()) {
			
			$data ['sku'] = trim($data ['sku']);
			$data ['manufacturer'] = trim(preg_replace('/[^\w\s-]/','',$data ['manufacturer']));
			$data ['model'] = trim(preg_replace('/[^\w\s-]/','',$data ['model']));
			$data ['number'] = trim(preg_replace('/[^\w\s-]/','',$data ['number']));

			$model = Mage::getModel('mmnadmin/mmnAdmin');		
			$model->setData($data)
				->setId($this->getRequest()->getParam('id'));
			
			try {
				
				$model->save();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('mmnadmin')->__('Item was successfully saved'));
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
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('mmnadmin')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
	}
 
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('mmnadmin/mmnAdmin');
				 
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
        $mmnadminIds = $this->getRequest()->getParam('mmnadmin');
        if(!is_array($mmnadminIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($mmnadminIds as $mmnadminId) {
                    $mmnadmin = Mage::getModel('mmnadmin/mmnAdmin')->load($mmnadminId);
                    $mmnadmin->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($mmnadminIds)
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
        $mmnadminIds = $this->getRequest()->getParam('mmnadmin');
        if(!is_array($mmnadminIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($mmnadminIds as $mmnadminId) {
                    $mmnadmin = Mage::getSingleton('mmnadmin/mmnAdmin')
                        ->load($mmnadminId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($mmnadminIds))
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
            ->_setActiveMenu('mmnadmin/import')
            ->_addContent($this->getLayout()->createBlock('mmnadmin/items_importExport'))
            ->renderLayout();
    }

    /**
     * import action from import/export mmnadmin
     *
     */
    public function importPostAction()
    {
        if ($this->getRequest()->isPost() && !empty($_FILES['import_mmnadmin_file']['tmp_name'])) {
            try {
                $number = $this->_importMmnAdmin();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('mmnadmin')->__('%d new item(s) were imported',$number));
            }
            catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('mmnadmin')->__('Invalid file upload attempt'));
            }
        }
        else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('mmnadmin')->__('Invalid file upload attempt'));
        }
        $this->_redirect('*/*/importExport');
    }

    protected function _importMmnAdmin()
    {
        $fileName   = $_FILES['import_mmnadmin_file']['tmp_name'];
        $csvObject  = new Varien_File_Csv();
        $csvData = $csvObject->getData($fileName);
		$number = 0;
        /** checks columns */
        $csvFields  = array(
            0   => Mage::helper('mmnadmin')->__('SKU'),
            1   => Mage::helper('mmnadmin')->__('Printer Manufacturer'),
            2   => Mage::helper('mmnadmin')->__('Printer Model'),
            3   => Mage::helper('mmnadmin')->__('Printer Number')
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
                    Mage::getSingleton('adminhtml/session')->addError(Mage::helper('mmnadmin')->__('Invalid file upload attempt'));
                }
				
				if (!empty($v[0])) {
					
				    $v[0] = trim($v[0]);					
				    $v[1] = trim(preg_replace('/[^\w\s-]/','',$v[1]));
					$v[2] = trim(preg_replace('/[^\w\s-]/','',$v[2]));
					$v[3] = trim(preg_replace('/[^\w\s-]/','',$v[3]));
					
					$resource = Mage::getSingleton('core/resource');
					$read= $resource->getConnection('core_read');
					$mmnadminTable = $resource->getTableName('mmnadmin/mmn');
					$select = $read->select()
											->from($mmnadminTable,array('mmn_id'))
											->where("sku=?",$v[0])
											->where("manufacturer=?",$v[1])
											->where("model=?",$v[2])
											->where("number=?",$v[3])										
											->limit(1);
											
					if($read->fetchOne($select)){
						 continue;
					}	 

					$data  = array(
						'sku'=>$v[0],
						'manufacturer' => $v[1],
						'model' => $v[2],
						'number'  => $v[3]
					);

					$model  = Mage::getModel('mmnadmin/mmnAdmin');
					$model->setData($data);
					$model->save();
					$number++;
                }
            }  
        }
        else {
            Mage::throwException(Mage::helper('mmnadmin')->__('Invalid file format upload attempt'));
        }
		
		return $number;
    }

    /**
     * export action from import/export tax
     *
     */
    public function exportPostAction()
    {
        $fileName   = 'mmnadmin.csv';
        $content    = $this->getLayout()->createBlock('mmnadmin/items_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }
	
}