<?php

namespace MG\Blog\Block;

use Magento\Framework\View\Element\Template;

class PostListing extends \Magento\Framework\View\Element\Template
{
    private $scopeConfig;
    protected $configData;
    protected $postCollectionFactory;
    protected $postTagCollectionFactory;
    protected $tagRepository;
    protected $pageSize;

    public function __construct(
        Template\Context $context,
        \MG\Blog\Model\ResourceModel\Post\CollectionFactory $postCollectionFactory,
        \MG\Blog\Model\ResourceModel\PostTag\CollectionFactory $postTagCollectionFactory,
        \MG\Blog\Model\TagRepository $tagRepository,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \MG\Blog\Helper\ConfigData $configData,
        array $data = []
    ) {
        $this->tagRepository = $tagRepository;
        $this->postTagCollectionFactory = $postTagCollectionFactory;
        $this->postCollectionFactory = $postCollectionFactory;
        $this->configData = $configData;
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context, $data);
    }

    public function _prepareLayout()
    {
        parent::_prepareLayout();
        $posts = $this->getPosts();
        if ($posts) {
            $pager = $this->getLayout()->createBlock(
                'Magento\Theme\Block\Html\Pager',
                'blog.pager'
            )
                ->setLimit($this->pageSize)
                ->setAvailableLimit([3=>3,5=>5,10=>10])
                ->setShowPerPage(true)->setCollection($posts);
            $this->setChild('pager', $pager);
            $posts->load();
        }

        return $this;
    }

    public function getImagePath()
    {
        $imagePath = $this->scopeConfig->getValue("web/unsecure/base_url");

        return $imagePath . 'pub/media/blog/image/';
    }

    public function getPostUrl()
    {
        return $this->getUrl('*/*/detail', ['_use_rewrite' => true]);
    }

    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    public function getPosts()
    {
        $this->pageConfig->getTitle()->set("Home");
        $categoryId = ($this->getRequest()->getParam('categoryId')) ? $this->getRequest()->getParam('categoryId') : null;
        $page = ($this->getRequest()->getParam('p')) ? $this->getRequest()->getParam('p') : 1;

        //get values of current limit
        $this->pageSize=($this->getRequest()->getParam('limit')) ?
            $this->getRequest()->getParam('limit') :
            $this->configData->getPaginationConfig("default_pagination");

        $now = new \DateTime();
        $collection = $this->postCollectionFactory->create();

        if ($categoryId !== null) {
            $collection->addFieldToSelect('*');
            $collection->getSelect()
                ->joinLeft(['second' => 'sosc_blog_post_category'], 'main_table.post_id = second.post_id');
            $collection->addFieldToFilter('category_id', ['eq' => $categoryId]);
        }

        if ($this->getRequest()->getPostValue()) {
            $this->pageConfig->getTitle()->set("Search Result");
            $collection->addFieldToFilter("name", ["like" => "%" . $this->getRequest()->getPostValue()["keyword"] . "%"]);
        }

        $collection->addFieldToFilter('status', ['eq' => 1])
            ->setOrder("publish_date_from", "desc")
            ->addFieldToFilter('publish_date_from', ['lteq' => $now->format('Y-m-d')])
            ->addFieldToFilter('publish_date_to', ['gteq' => $now->format('Y-m-d')])
            ->setPageSize($this->pageSize)
            ->setCurPage($page);

        // Get tags
        foreach ($collection as $key => $item) {
            $tags = [];
            $postId = $item->getData("post_id");
            $tagCollection = $this->postTagCollectionFactory->create()
                ->addFieldToFilter("post_id", ['eq' => $postId]);
            foreach ($tagCollection as $tagItem) {
                $tag = $this->tagRepository->getById($tagItem->getData("tag_id"));
                array_push($tags, $tag->getData());
            }
            $collection->getItems()[$key]->setData("tags", $tags);
        }
        return $collection;

    }
}
