<?php
class Newsletter_News_Block_Adminhtml_Page_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        //echo "in";die;
        parent::__construct();
        $this->setId('newsGrid');
        $this->setDefaultSort('newspost_id');
        $this->setDefaultDir('ASC');
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('news/newspost')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('newspost_id', array(
            'header'   => Mage::helper('news')->__('Id'),
            'index'    => 'newspost_id',
            'width'    => 50
        ));

        $this->addColumn('title', array(
            'header'   => Mage::helper('news')->__('Question'),
            'index'    => 'title'
        ));

        $this->addColumn('post', array(
            'header'   => Mage::helper('news')->__('Answer'),
            'index'    => 'post'
        ));

        $this->addColumn('updated_at', array(
            'header'   => Mage::helper('news')->__('Updated_at'),
            'index'    => 'updated_at'
        ));

        $this->addColumn('created_at', array(
            'header'   => Mage::helper('news')->__('Created_at'),
            'index'    => 'created_at'
        ));

        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('store_id', array(
                'header'        => Mage::helper('cms')->__('Store View'),
                'index'         => 'store_id',
                'type'          => 'store',
                'store_all'     => true,
                'store_view'    => true,
                'sortable'      => false,
                'filter_condition_callback'
                => array($this, '_filterStoreCondition')
            ));
        }

        $this->addColumn('date', array(
            'header'   => Mage::helper('news')->__('Created Date'),
            'index'    => 'date',
            'width'    => 150,
            'type' => 'datetime'
        ));

        $this->addColumn('status', array(
            'header'   => Mage::helper('news')->__('Status'),
            'index'    => 'status',
            'type'     => 'options',
            'width'    => 150,
            'options'  => Mage::getSingleton('news/newspost')->getAvailableStatuses()
        ));
        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('newspost_id');
        $this->getMassactionBlock()->setFormFieldName('news');

        if ($this->_isAllowedAction('delete')) {
            $this->getMassactionBlock()->addItem('delete', array(
                'label'=> Mage::helper('catalog')->__('Delete'),
                'url'  => $this->getUrl('*/*/massDelete'),
                'confirm' => Mage::helper('catalog')->__('Are you sure?')
            ));
        }

        if ($this->_isAllowedAction('approve')) {
            $statuses = Mage::getSingleton('news/newspost')->getAvailableStatuses();

            array_unshift($statuses, array('label'=>'', 'value'=>''));
            $this->getMassactionBlock()->addItem('status', array(
                'label'=> Mage::helper('catalog')->__('Change status'),
                'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                'additional' => array(
                    'visibility' => array(
                        'name' => 'status',
                        'type' => 'select',
                        'class' => 'required-entry',
                        'label' => Mage::helper('catalog')->__('Status'),
                        'values' => $statuses
                    )
                )
            ));
        }

        return $this;
    }

    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }

    protected function _filterStoreCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $this->getCollection()->addStoreFilter($value);
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('newspost_id' => $row->getNewspostId()));
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