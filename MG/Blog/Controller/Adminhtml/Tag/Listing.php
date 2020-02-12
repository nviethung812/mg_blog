<?php

namespace MG\Blog\Controller\Adminhtml\Tag;

class Listing extends \Magento\Backend\App\Action
{
    const ADMIN_RESOURCE = "MG_Blog::manage_tag";

    protected $resultPageFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__("Manage Tag"));
        return $resultPage;
    }
}
