<?php

namespace MG\Blog\Model\ResourceModel;

class PostCategory extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init("sosc_blog_post_category", "id");
    }

}
