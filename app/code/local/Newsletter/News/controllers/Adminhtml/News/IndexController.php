<?php
class Newsletter_News_Adminhtml_News_IndexController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Init actions
     *
     */
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('templates/news_index/index')
            ->_addBreadcrumb(
                Mage::helper('news')->__('News'),
                Mage::helper('news')->__('Manage Test')
            );
        return $this;
    }

    public function indexAction()
    {
        $this->_title($this->__('Templates'))
            ->_title($this->__('News'))
            ->_title($this->__('Manage Test'));

        $this->_initAction();
        $this->renderLayout();
    }

    public function newAction()
    {
        // the same form is used to create and edit
        $this->_forward('edit');
    }

    public function editAction()
    {
        $this->_title($this->__('Templates'))
            ->_title($this->__('News'))
            ->_title($this->__('Manage Test'));

        $id = $this->getRequest()->getParam('newspost_id');
        $model = Mage::getModel('news/newspost');

        if ($id) {
            $model->load($id);
            if (! $model->getId()) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('cms')->__('This page no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
        }

        $this->_title($model->getId() ? $model->getName() : $this->__('New Table'));

        $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
        if (! empty($data)) {
            $model->setData($data);
        }

        Mage::register('newspost_data', $model);

        $this->_initAction()
            ->_addBreadcrumb(
                $id ? Mage::helper('news')->__('Edit Table')
                    : Mage::helper('news')->__('New Table'),
                $id ? Mage::helper('news')->__('Edit Table')
                    : Mage::helper('news')->__('New Table'));

        $this->renderLayout();
    }

    public function saveAction()
    {
        if (!$data = $this->getRequest()->getPost('news')) {
            $this->_redirect('*/*/');
            return;
        }

        $model = Mage::getModel('news/newspost');
        if ($id = $this->getRequest()->getParam('newspost_id')) {
            $model->load($id);
        }

        try {
            $mediaPath = Mage::getBaseDir('media') . DS . Newsletter_News_Model_Newspost::IMAGE_PATH;
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                try {
                    $uploader = new Varien_File_Uploader('image');
                    $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png', 'bmp'));
                    $uploader->setAllowRenameFiles(true);
                    $uploader->setFilesDispersion(true);
                    if (@class_exists('Mage_Core_Model_File_Validator_Image')) {
                        $uploader->addValidateCallback(
                            Mage_Core_Model_File_Validator_Image::NAME,
                            Mage::getModel('core/file_validator_image'),
                            'validate'
                        );
                    }
                    $res = $uploader->save($mediaPath);
                    $data['image'] = $uploader->getUploadedFileName();
                } catch (Exception $e) {
                    $this->_getSession()->addError($e->getMessage());
                }
            }

            if (isset($data['image']) && is_array($data['image'])) {
                if (!empty($data['image']['delete'])) {
                    @unlink($mediaPath . $data['image']['value']);
                    $data['image'] = null;
                } else {
                    $data['image'] = $data['image']['value'];
                }
            }

            $model->addData($data);

            $date = Mage::app()->getLocale()->date($data['date'], Zend_Date::DATE_SHORT, null, false);
            $model->setDate($date->toString('YYYY-MM-dd HH:mm:ss'));

            $model->save();

            // clear testimonials list block cache after new item was added
            // Mage::app()->cleanCache(array('tm_testimonials_list'));

            Mage::getSingleton('adminhtml/session')->addSuccess(
                Mage::helper('news')->__('Table has been saved.')
            );
            Mage::getSingleton('adminhtml/session')->setFormData(false);
            if ($this->getRequest()->getParam('back')) {
                $this->_redirect('*/*/edit', array('newspost_id' => $model->getId(), '_current' => true));
                return;
            }
            $this->_redirect('*/*/');
            return;
        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }
        $this->_getSession()->setFormData($data);
        $this->_redirect('*/*/edit', array('newspost_id' => $this->getRequest()->getParam('newspost_id'), '_current'=>true));
    }

    public function massDeleteAction()
    {
        $newspostIds = $this->getRequest()->getParam('news');
        if (!is_array($newspostIds)) {
            $this->_getSession()->addError($this->__('Please select testimonial(s).'));
        } else {
            if (!empty($newspostIds)) {
                try {
                    foreach ($newspostIds as $newspostId) {
                        $news = Mage::getModel('news/newspost')->load($newspostId);
                        $news->delete();
                    }
                    $this->_getSession()->addSuccess(
                        $this->__('Total of %d record(s) have been deleted.', count($newspostIds))
                    );
                } catch (Exception $e) {
                    $this->_getSession()->addError($e->getMessage());
                }
            }
        }
        $this->_redirect('*/*/index');
    }
}
?>