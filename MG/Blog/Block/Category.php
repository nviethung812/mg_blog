<?php

namespace MG\Blog\Block;

use Magento\Framework\View\Element\Template;

class Category extends \Magento\Framework\View\Element\Template
{
    protected $categoryCollectionFactory;

    public function __construct(
        \MG\Blog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        Template\Context $context,
        array $data = []
    ) {
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        parent::__construct($context, $data);
    }

    public function getBlogUrl()
    {
        return $this->getUrl('*/*/listing', ['_use_rewrite' => true]);
    }

    public function getCategoryUrl()
    {
        return $this->getUrl('*/*/*', ['_use_rewrite' => true]) . 'categoryId/';
    }

//    public function getSearchPath()
//    {
//        return $this->getUrl('*/*/search', ['_use_rewrite' => true]);
//    }

    public function getCategories()
    {
//        $this->_construct();
        $categoryData = [];
        $categories = $this->categoryCollectionFactory->create();
        foreach ($categories as $category) {
            if (!$category->hasChildren()) {
                array_push($categoryData, [
                    "id" => $category->getId(),
                    "name" => $category->getName()
                ]);
            }
        }
        return $categoryData;
    }


}
