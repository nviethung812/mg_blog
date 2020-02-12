<?php

namespace MG\Blog\Model\ResourceModel\PostCategory;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'id';

    protected function _construct()
    {
        $this->_init('MG\Blog\Model\PostCategory', 'MG\Blog\Model\ResourceModel\PostCategory');
    }
}
