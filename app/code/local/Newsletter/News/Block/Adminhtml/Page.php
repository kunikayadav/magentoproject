<?php

class Newsletter_News_Block_Adminhtml_Page extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        //echo "in";die;
        $this->_blockGroup = 'test';
        $this->_controller = 'adminhtml_page';
        $this->_headerText = Mage::helper('news')->__('Manage FAQ');

        parent::__construct();

        if (!$this->_isAllowedAction('save')) {
            $this->_removeButton('add');
        }
    }

    /**
     * Check permission for passed action
     *
     * @param string $action
     * @return bool
     */
    protected function _isAllowedAction($action)
    {
        return Mage::getSingleton('admin/session')->isAllowed('templates/news/news/' . $action);
    }
}
