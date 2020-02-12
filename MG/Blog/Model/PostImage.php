<?php


namespace MG\Blog\Model;


class PostImage extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'sosc_blog_post_image';

    protected function _construct()
    {
        $this->_init('MG\Blog\Model\ResourceModel\PostImage');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
}
