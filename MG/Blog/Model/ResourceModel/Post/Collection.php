<?php

namespace MG\Blog\Model\ResourceModel\Post;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'post_id';

    protected function _construct()
    {
        $this->_init('MG\Blog\Model\Post', 'MG\Blog\Model\ResourceModel\Post');
    }


}
