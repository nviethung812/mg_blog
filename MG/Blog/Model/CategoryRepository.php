<?php

namespace MG\Blog\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class CategoryRepository implements \MG\Blog\Api\CategoryRepositoryInterface
{
    protected $resource;
    protected $categoryFactory;
    protected $categoryCollectionFactory;

    /**
     * @var CollectionProcessorInterface
     */

    public function __construct(
        \MG\Blog\Model\ResourceModel\Category $resource,
        \MG\Blog\Model\CategoryFactory $categoryFactory,
        \MG\Blog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory
    ) {
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->categoryFactory = $categoryFactory;
        $this->resource = $resource;
    }

    public function save(\MG\Blog\Api\Data\CategoryInterface $category)
    {
        try {
            $categoryModel = $this->categoryFactory->create()->setData($this->_prepareData($category));
            $this->resource->save($categoryModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $category;
    }

    public function getById($categoryId)
    {
        $category = $this->categoryFactory->create();
        $this->resource->load($category, $categoryId);
        if (!$category->getId()) {
            throw new NoSuchEntityException(__('The category with the "%1" ID doesn\'t exist.', $categoryId));
        }
        return $category;
    }

    public function delete(\MG\Blog\Api\Data\CategoryInterface $category)
    {
        try {
            $categoryModel = $this->categoryFactory->create()->setData($this->_prepareData($category));
            $this->resource->delete($categoryModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    private function _prepareData(\MG\Blog\Api\Data\CategoryInterface $category)
    {
        return [
            "id" => $category->getId(),
            "name" => $category->getName()
        ];
    }

}
