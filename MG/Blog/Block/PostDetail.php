<?php

namespace MG\Blog\Block;

use Magento\Framework\View\Element\Template\Context;

class PostDetail extends \Magento\Framework\View\Element\Template
{
    protected $resultPageFactory;
    protected $postProductCollectionFactory;
    protected $postImageCollectionFactory;
    protected $productRepository;
    protected $productHelper;
    protected $storeManager;
    protected $postFactory;
    protected $post;
    protected $postRepository;
    protected $scopeConfig;

    public function __construct(
        Context $context,
        \MG\Blog\Model\ResourceModel\PostProduct\CollectionFactory $postProductCollectionFactory,
        \MG\Blog\Model\ResourceModel\PostImage\CollectionFactory $postImageCollectionFactory,
        \MG\Blog\Model\PostFactory $postFactory,
        \MG\Blog\Model\PostRepository $postRepository,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Catalog\Helper\Product $productHelper,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        array $data = []
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->postRepository = $postRepository;
        $this->postFactory = $postFactory;
        $this->productHelper = $productHelper;
        $this->productRepository = $productRepository;
        $this->postProductCollectionFactory = $postProductCollectionFactory;
        $this->postImageCollectionFactory = $postImageCollectionFactory;
        $this->storeManager = $storeManager;
        parent::__construct($context, $data);
    }

    public function _prepareLayout()
    {
        $id = $this->getRequest()->getParam("postId");
        $this->post = $this->postRepository->getById($id);
        $this->pageConfig->getTitle()->set($this->post->getName());
        return parent::_prepareLayout();
    }

    public function getProducts()
    {
        // Related product handle
        $products = [];
        $collection = $this->postProductCollectionFactory->create()
            ->addFieldToFilter('post_id', ['eq' => $this->post->getPostId()])
            ->setPageSize(3);
        foreach ($collection as $col) {
            $product = $this->productRepository->getById($col->getProductId());
            $productThumbnail = $this->productHelper->getThumbnailUrl($product);

            if (substr($productThumbnail, strlen($productThumbnail) - 12) == "no_selection") {
                $productThumbnail = $this->getImagePath() . "default.png";
            }

            $price = ($product->getPrice() != 0) ? strval(number_format($product->getPrice(), 2)) : 0;
            $url = $this->storeManager->getStore()->getBaseUrl() . $product->getUrlKey() . ".html";

            array_push($products, [
                "name" => $product->getName(),
                "price" => $price,
                "thumbnail" => $productThumbnail,
                "url" => $url
            ]);
        }
//        die;
        return $products;
    }

    public function getPostData()
    {
        return $this->post->getData();
    }

    public function getGallery()
    {
        $gallery = [];
        $imageCollection = $this->postImageCollectionFactory->create()
            ->addFieldToFilter("post_id", ['eq' => $this->post->getPostId()]);
        foreach ($imageCollection as $imageItem) {
            array_push($gallery, $imageItem->getImageName());
        }
        return $gallery;
    }

    public function getImagePath()
    {
        $imagePath = $this->scopeConfig->getValue("web/unsecure/base_url");

        return $imagePath . 'pub/media/blog/image/';
    }
}
