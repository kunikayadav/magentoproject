<?php
class Newsletter_News_Model_Resource_Newspost extends Mage_Core_Model_Resource_Db_Abstract{
    protected function _construct()
    {
        $this->_init('news/newspost', 'newspost_id');
    }
}