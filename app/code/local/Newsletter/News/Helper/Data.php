<?php
class Newsletter_News_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getAllblog()
    {
        $posts = Mage::getModel('news/newspost')->getCollection();
        return $posts->getData();
    }
    const XML_PATH_ENABLED = 'news/general/enabled';
    public function isEnabled($store = null)
    {
        //var_dump(Mage::getStoreConfigFlag(self::XML_PATH_ENABLED, $store));die;

        return Mage::getStoreConfigFlag(self::XML_PATH_ENABLED, $store);
    }

}
?>