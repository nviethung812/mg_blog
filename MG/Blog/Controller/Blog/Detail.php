<?php

namespace MG\Blog\Controller\Blog;

use Magento\Framework\App\Action\Context;

class Detail extends \Magento\Framework\App\Action\Action
{
    protected $postFactory;
    protected $resultPageFactory;
    protected $postProductCollectionFactory;
    protected $productRepository;
    protected $productHelper;
    protected $storeManager;

    public function __construct(
        Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \MG\Blog\Model\PostFactory $postFactory,
        \MG\Blog\Model\ResourceModel\PostProduct\CollectionFactory $postProductCollectionFactory,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Catalog\Helper\Product $productHelper,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->productHelper = $productHelper;
        $this->productRepository = $productRepository;
        $this->postProductCollectionFactory = $postProductCollectionFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->postFactory = $postFactory;
        $this->storeManager = $storeManager;
        parent::__construct($context);
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();

        $blockContent = $resultPage->getLayout()->getBlock('post.content');

        $blockContent->setData("backUrl", $this->previousUrl());

        return $resultPage;
    }

    public function previousUrl()
    {
        return $this->_redirect->getRefererUrl();
    }
}
