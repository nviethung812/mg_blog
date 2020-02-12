<?php

namespace MG\Blog\Model;

class Tag extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface, \MG\Blog\Api\Data\TagInterface
{
    const CACHE_TAG = 'sosc_blog_tag';

    protected function _construct()
    {
        $this->_init('MG\Blog\Model\ResourceModel\Tag');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return $this->getData(self::NAME);
    }

    /**
     * @inheritDoc
     */
    public function setName($name)
    {
        $this->setData(self::NAME, $name);
    }
}
