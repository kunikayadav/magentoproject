<?php
class Newsletter_News_Model_Resource_Newspost_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract {
    protected function _construct()
    {
        $this->_init('news/newspost');
    }
}