<?php


namespace MG\Blog\Model;


class PostProduct extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'sosc_blog_post_product';

    protected function _construct()
    {
        $this->_init('MG\Blog\Model\ResourceModel\PostProduct');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
}
