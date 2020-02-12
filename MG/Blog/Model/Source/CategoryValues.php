<?php

namespace MG\Blog\Model\Source;

use Magento\Framework\Option\ArrayInterface;

class CategoryValues implements ArrayInterface
{
    protected $storeManager;
    protected $categoryCollectionFactory;

    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \MG\Blog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory
    ) {
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->storeManager = $storeManager;
    }

    /**
     * @inheritDoc
     */
    public function toOptionArray()
    {
        $categories = $this->categoryCollectionFactory->create();
        $data = [];

        foreach ($categories as $category) {
            array_push($data, ['value' => $category->getId(), 'label' => $category->getName()]);
        }

        return $data;
    }
}
