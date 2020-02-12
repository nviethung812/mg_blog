<?php

namespace MG\Blog\Model\Post;

use Magento\Store\Model\StoreManagerInterface;
use MG\Blog\Model\ResourceModel\Post\CollectionFactory;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    protected $collection;
    protected $_loadedData;
    protected $storeManager;

    protected $postCategoryCollectionFactory;
    protected $postProductCollectionFactory;
    protected $postTagCollectionFactory;
    protected $postImageCollectionFactory;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $postCollectionFactory,
        StoreManagerInterface $storeManager,
        \MG\Blog\Model\ResourceModel\PostCategory\CollectionFactory $postCategoryCollectionFactory,
        \MG\Blog\Model\ResourceModel\PostProduct\CollectionFactory $postProductCollectionFactory,
        \MG\Blog\Model\ResourceModel\PostTag\CollectionFactory $postTagCollectionFactory,
        \MG\Blog\Model\ResourceModel\PostImage\CollectionFactory $postImageCollectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->postTagCollectionFactory = $postTagCollectionFactory;
        $this->postProductCollectionFactory = $postProductCollectionFactory;
        $this->postCategoryCollectionFactory = $postCategoryCollectionFactory;
        $this->postImageCollectionFactory = $postImageCollectionFactory;
        $this->collection = $postCollectionFactory->create();
        $this->storeManager = $storeManager;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData()
    {
        if (isset($this->_loadedData)) {
            return $this->_loadedData;
        }
        $items = $this->collection->getItems();

        foreach ($items as $post) {
            $this->_loadedData[$post->getId()] = $post->getData();

            // Get thumbnail for editing
            if ($post->getThumbnail()) {
                $p['thumbnail'][0]['name'] = $post->getThumbnail();
                $p['thumbnail'][0]['url'] = $this->getMediaUrl() . $post->getThumbnail();
                $fullData = $this->_loadedData;
                $this->_loadedData[$post->getId()] = array_merge($fullData[$post->getId()], $p);
            }

            // Get selected category for editing
            $categories = [];
            foreach ($this->postCategoryCollectionFactory->create()->addFieldToFilter('post_id', ['eq' => $post->getId()]) as $postCategory) {
                array_push($categories, $postCategory->getCategoryId());
            }
            $this->_loadedData[$post->getId()]['categories'] = $categories;

            // Get selected product
            $products = [];
            foreach ($this->postProductCollectionFactory->create()->addFieldToFilter('post_id', ['eq' => $post->getId()]) as $postProduct) {
                array_push($products, $postProduct->getProductId());
            }
            $this->_loadedData[$post->getId()]['products'] = $products;

            // Get selected tag
            $tags = [];
            foreach ($this->postTagCollectionFactory->create()->addFieldToFilter('post_id', ['eq' => $post->getId()]) as $postTag) {
                array_push($tags, $postTag->getTagId());
            }
            $this->_loadedData[$post->getId()]['tags'] = $tags;

            // Get gallery
            $gallery = [];
            foreach ($this->postImageCollectionFactory->create()->addFieldToFilter('post_id', ['eq' => $post->getId()]) as $image) {
                $imageName = $image->getImageName();
                array_push($gallery, [
                    "name" => $imageName,
                    "url" => $this->getMediaUrl() . $imageName,
                    "size" => $image->getImageSize()
                ]);
            }
            $this->_loadedData[$post->getId()]['gallery'] = $gallery;
        }
//        var_dump($this->_loadedData);die;
        return $this->_loadedData;
    }

    public function getMediaUrl()
    {
        return $this->storeManager->getStore()
                ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'blog/image/';
    }
}
