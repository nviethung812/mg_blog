<?php

namespace MG\Blog\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class TagRepository implements \MG\Blog\Api\TagRepositoryInterface
{
    protected $resource;
    protected $tagFactory;
    protected $tagCollectionFactory;

    /**
     *
     * @param ResourceModel\Tag $resource
     * @param TagFactory $tagFactory
     * @param ResourceModel\Tag\CollectionFactory $tagCollectionFactory
     */

    public function __construct(
        \MG\Blog\Model\ResourceModel\Tag $resource,
        \MG\Blog\Model\TagFactory $tagFactory,
        \MG\Blog\Model\ResourceModel\Tag\CollectionFactory $tagCollectionFactory
    ) {
        $this->tagCollectionFactory = $tagCollectionFactory;
        $this->tagFactory = $tagFactory;
        $this->resource = $resource;
    }

    public function save(\MG\Blog\Api\Data\TagInterface $tag)
    {
        try {
            $tagModel = $this->tagFactory->create()->setData($this->_prepareData($tag));
            $this->resource->save($tagModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $tag;
    }

    public function getById($tagId)
    {
        $tag = $this->tagFactory->create();
        $this->resource->load($tag, $tagId);
        if (!$tag->getId()) {
            throw new NoSuchEntityException(__('The tag with the "%1" ID doesn\'t exist.', $tagId));
        }
        return $tag;
    }

    public function delete(\MG\Blog\Api\Data\TagInterface $tag)
    {
        try {
            $tag = $this->tagFactory->create()->setData($this->_prepareData($tag));
            $this->resource->delete($tag);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    private function _prepareData(\MG\Blog\Api\Data\TagInterface $tag)
    {
        return [
            "id" => $tag->getId(),
            "name" => $tag->getName()
        ];
    }
}
