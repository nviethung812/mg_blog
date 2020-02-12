<?php

namespace MG\Blog\Model\ResourceModel;

class Tag extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('sosc_blog_tag', 'id');
    }
}
