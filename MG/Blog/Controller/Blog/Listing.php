<?php

namespace MG\Blog\Controller\Blog;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\View\Result\PageFactory;

class Listing extends \Magento\Framework\App\Action\Action
{
    protected $pageFactory;
    protected $categoryRepository;
    protected $pageSize;

    public function __construct(
        Context $context,
        \MG\Blog\Model\CategoryRepository $categoryRepository,
        PageFactory $pageFactory
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->pageFactory = $pageFactory;
        parent::__construct($context);
    }

    /**
     * Execute action based on request and return result
     *
     * Note: Request will be added as operation argument in future
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     * @throws \Exception
     */
    public function execute()
    {
        // Get param from request
        $categoryId = $this->_request->getParam("categoryId");
        $page = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        // Set data for each block
        if ($categoryId) {
            $page->getConfig()
                ->getTitle()
                ->set($this->categoryRepository->getById($categoryId)->getName());
        }

        return $page;
    }
}
