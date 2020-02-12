<?php


namespace MG\Blog\Model;


class PostTag extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'sosc_blog_post_tag';

    protected function _construct()
    {
        $this->_init('MG\Blog\Model\ResourceModel\PostTag');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
}
