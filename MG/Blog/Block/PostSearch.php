<?php

namespace MG\Blog\Block;

use Magento\Framework\View\Element\Template;

class PostSearch extends \Magento\Framework\View\Element\Template
{
    private $scopeConfig;
    protected $listingController;
    protected $configData;

    public function __construct(
        Template\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \MG\Blog\Controller\Blog\Listing $listingController,
        \MG\Blog\Helper\ConfigData $configData,
        array $data = []
    ) {
        $this->configData = $configData;
        $this->listingController = $listingController;
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
                ->setLimit($this->listingController->getPageSize())
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

        return $imagePath . 'pub/media/blog/thumb/';
    }

    public function getPostUrl()
    {
        return $this->getUrl('*/*/detail', ['_use_rewrite' => true]);
    }

    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    public function getPosts($keyWord = '')
    {
//        $keyWord = $this->getRequest()->getParam('key');
        $categoryId = ($this->getRequest()->getParam('categoryId')) ? $this->getRequest()->getParam('categoryId') : null;
        $page = ($this->getRequest()->getParam('p')) ? $this->getRequest()->getParam('p') : 1;
        //get values of current limit
        $pageSize=($this->getRequest()->getParam('limit')) ? $this->getRequest()->getParam('limit') : null;
        return $this->listingController->getPostCollection($categoryId, $pageSize, $page, $keyWord);
    }
}
