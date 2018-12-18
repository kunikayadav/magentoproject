<?php
class Newsletter_News_Model_Newspost extends Mage_Core_Model_Abstract
{
    /**
     * Testimonial's Statuses
     */
    const STATUS_AWAITING_APPROVAL = 1;
    const STATUS_ENABLED = 2;
    const STATUS_DISABLED = 3;

    const IMAGE_PATH = '/testimonials/pictures/';

    const CACHE_TAG = 'news_data';
    protected $_cacheTag = self::CACHE_TAG;

    protected function _construct()
    {
        $this->_init('news/newspost');
        parent::_construct();
    }

    public function getAvailableStatuses()
    {
        $statuses = new Varien_Object(array(
            self::STATUS_AWAITING_APPROVAL => Mage::helper('news')->__('Awaiting approval'),
            self::STATUS_ENABLED => Mage::helper('cms')->__('Enabled'),
            self::STATUS_DISABLED => Mage::helper('cms')->__('Disabled')
        ));

        Mage::dispatchEvent('news_data_get_available_statuses', array('statuses' => $statuses));

        return $statuses->getData();
    }
    /**
     * Get cache tags associated with object id
     *
     * @return array
     */
}