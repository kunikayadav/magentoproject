<?php
class Newsletter_News_Block_Monblock extends Mage_Core_Block_Template
{
    public function getAllblog()
    {
        $posts = Mage::getModel('news/newspost')->getCollection();
        return $posts->getData();
    }
}
?>