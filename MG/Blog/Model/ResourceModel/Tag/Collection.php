<?php

namespace MG\Blog\Model\ResourceModel\Tag;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'id';

    protected function _construct()
    {
        $this->_init('MG\Blog\Model\Tag', 'MG\Blog\Model\ResourceModel\Tag');
    }
}
