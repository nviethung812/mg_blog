<?php

namespace MG\Blog\Model;

use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class PostRepository implements \MG\Blog\Api\PostRepositoryInterface
{
    protected $resource;
    protected $postFactory;
    protected $postCollectionFactory;

    public function __construct(
        \MG\Blog\Model\ResourceModel\Post $resource,
        \MG\Blog\Model\PostFactory $postFactory,
        \MG\Blog\Model\ResourceModel\Post\CollectionFactory $postCollectionFactory
    ) {
        $this->postCollectionFactory = $postCollectionFactory;
        $this->postFactory = $postFactory;
        $this->resource = $resource;
    }

    public function save(\MG\Blog\Api\Data\PostInterface $post)
    {
        try {
            $postModel = $this->postFactory->create()->setData($this->_prepareData($post));
            $this->resource->save($postModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $postModel;
    }

    public function getById($postId)
    {
        $post = $this->postFactory->create();
        $this->resource->load($post, $postId);
        if (!$post->getId()) {
            throw new NoSuchEntityException(__('The post with the "%1" ID doesn\'t exist.', $postId));
        }
        return $post;
    }

    public function delete(\MG\Blog\Api\Data\PostInterface $post)
    {
        try {
            $postModel = $this->postFactory->create()->setData($this->_prepareData($post));
            $this->resource->delete($postModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    private function _prepareData(\MG\Blog\Api\Data\PostInterface $post)
    {
        return [
            "post_id" => $post->getPostId(),
            "name" => $post->getName(),
            "short_description" => $post->getShortDescription(),
            "description" => $post->getDescription(),
            "content" => $post->getContent(),
            "status" => $post->getStatus(),
            "url_key" => $post->getUrlKey(),
            "publish_date_from" => $post->getPublishDateFrom(),
            "publish_date_to" => $post->getPublishDateTo(),
            "creation_time" => $post->getCreationTime(),
            "update_time" => $post->getUpdateTime(),
            "thumbnail" => $post->getThumbnail()
        ];
    }

    public function isNameExists($name)
    {
        $post = $this->postFactory->create();
        $collection = $this->postCollectionFactory->create()->addFieldToFilter("name", ["eq" => $name]);
        if (!count($collection->getData())) {
            return false;
        }

        return true;
    }
}
