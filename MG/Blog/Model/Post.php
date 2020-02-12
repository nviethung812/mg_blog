<?php

namespace MG\Blog\Model;

class Post extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface, \MG\Blog\Api\Data\PostInterface
{
    const CACHE_TAG = 'sosc_blog_post';

    protected function _construct()
    {
        $this->_init('MG\Blog\Model\ResourceModel\Post');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getPostId()
    {
        return $this->getData(self::POST_ID);
    }

    public function getName()
    {
        return $this->getData(self::NAME);
    }

    public function getShortDescription()
    {
        return $this->getData(self::SHORT_DESCRIPTION);
    }

    public function getDescription()
    {
        return $this->getData(self::DESCRIPTION);
    }

    public function getContent()
    {
        return $this->getData(self::CONTENT);
    }

    public function getStatus()
    {
        return $this->getData(self::STATUS);
    }

    public function getUrlKey()
    {
        return $this->getData(self::URL_KEY);
    }

    public function getPublishDateFrom()
    {
        return $this->getData(self::PUBLISH_DATE_FROM);
    }

    public function getPublishDateTo()
    {
        return $this->getData(self::PUBLISH_DATE_TO);
    }

    public function getCreationTime()
    {
        return $this->getData(self::CREATION_TIME);
    }

    public function getUpdateTime()
    {
        return $this->getData(self::UPDATE_TIME);
    }

    public function getThumbnail()
    {
        return $this->getData(self::THUMBNAIL);
    }

    public function setName($name)
    {
        $this->setData(self::NAME, $name);
    }

    public function setShortDescription($shortDescription)
    {
        $this->setData(self::SHORT_DESCRIPTION, $shortDescription);
    }

    public function setDescription($description)
    {
        $this->setData(self::DESCRIPTION, $description);
    }

    public function setContent($content)
    {
        $this->setData(self::CONTENT, $content);
    }

    public function setStatus($status)
    {
        $this->setData(self::STATUS, $status);
    }

    public function setUrlKey($urlKey)
    {
        $this->setData(self::URL_KEY, $urlKey);
    }

    public function setPublishDateFrom($publishDateFrom)
    {
        $this->setData(self::PUBLISH_DATE_FROM, $publishDateFrom);
    }

    public function setPublishDateTo($publishDateTo)
    {
        $this->setData(self::PUBLISH_DATE_TO, $publishDateTo);
    }

    public function setCreationTime($creationTime)
    {
        $this->setData(self::CREATION_TIME, $creationTime);
    }

    public function setUpdateTime($updateTime)
    {
        $this->setData(self::UPDATE_TIME, $updateTime);
    }

    public function setThumbnail($thumbnail)
    {
        $this->setData(self::THUMBNAIL, $thumbnail);
    }
}
