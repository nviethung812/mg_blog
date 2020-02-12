<?php

namespace MG\Blog\Block;

use Magento\Framework\View\Element\Template;

class PostImage extends \Magento\Framework\View\Element\Template
{
    protected $scopeConfig;
    protected $post;
    protected $postRepository;
    protected $postFactory;

    public function __construct(
        Template\Context $context,
        \MG\Blog\Model\PostFactory $postFactory,
        \MG\Blog\Model\PostRepository $postRepository,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        array $data = []
    ) {
        $this->postRepository = $postRepository;
        $this->postFactory = $postFactory;
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context, $data);
    }
    protected function _prepareLayout()
    {
        $id = $this->getRequest()->getParam("postId");
        $this->post = $this->postRepository->getById($id);
        $this->pageConfig->getTitle()->set($this->post->getName());
        return parent::_prepareLayout();
    }

    public function getImagePath()
    {
        $imagePath = $this->scopeConfig->getValue("web/unsecure/base_url");

        return $imagePath . 'pub/media/blog/image/';
    }

    public function getPostThumbnail()
    {
        return $this->post->getData("thumbnail");
    }
}
