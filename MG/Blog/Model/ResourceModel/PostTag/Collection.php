<?php


namespace MG\Blog\Model\ResourceModel\PostTag;


class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'id';

    protected function _construct()
    {
        $this->_init('MG\Blog\Model\PostTag', 'MG\Blog\Model\ResourceModel\PostTag');
    }
}
