<?php

class Newsletter_News_Block_Adminhtml_Page_Edit_Tab_Main
    extends Mage_Adminhtml_Block_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    protected function _prepareForm()
    {
        $model = Mage::registry('newspost_data');
        $form  = new Varien_Data_Form();
        $form->setHtmlIdPrefix('page_');

        $fieldset = $form->addFieldset('base_fieldset', array(
            'legend' => Mage::helper('news')->__('FAQ Information'),
            'class' => 'fieldset-wide'
        ));

        if ($model->getNewspostId()) {
            $fieldset->addField('newspost_id', 'hidden', array(
                'name'  => 'newspost_id'
            ));
        }

        if ($this->_isAllowedAction('save')) {
            $isElementDisabled = false;
        } else {
            $isElementDisabled = true;
        }

        $fieldset->addField('title', 'text', array(
            'name'     => 'title',
            'label'    => Mage::helper('catalog')->__('Question'),
            'title'    => Mage::helper('catalog')->__('Name'),
            'required' => true,
            'disabled' => $isElementDisabled
        ));

        $fieldset->addField('post', 'text', array(
            'name'     => 'post',
            'label'    => Mage::helper('contacts')->__('Answer'),
            'title'    => Mage::helper('contacts')->__('Email'),
            'required' => true,
            // 'class'    => 'validate-email',
            'disabled' => $isElementDisabled
        ));

        $fieldset->addField('created_at', 'date', array(
            'name'     => 'created_at',
            'label'    => Mage::helper('news')->__('Created date'),
            'title'    => Mage::helper('news')->__('Created date'),
            'image'    => $this->getSkinUrl('images/grid-cal.gif'),
            'format'   => Mage::app()->getLocale()->getDateFormatWithLongYear(),
            'disabled' => $isElementDisabled
        ));

        $form->addValues($model->getData());
        $form->setFieldNameSuffix('news');
        $this->setForm($form);
        return parent::_prepareForm();
    }

    protected function _getAdditionalElementTypes()
    {
        return array(
            'image' => Mage::getConfig()->getBlockClassName('news/adminhtml_page_helper_image')
        );
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return Mage::helper('news')->__('FAQ Information');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return Mage::helper('news')->__('FAQ Information');
    }

    /**
     * Returns status flag about this tab can be shown or not
     *
     * @return true
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Returns status flag about this tab hidden or not
     *
     * @return true
     */
    public function isHidden()
    {
        return false;
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