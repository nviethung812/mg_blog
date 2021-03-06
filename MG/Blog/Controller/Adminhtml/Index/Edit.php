<?php

namespace MG\Blog\Controller\Adminhtml\Index;

class Edit extends \Magento\Backend\App\Action
{
    const ADMIN_RESOURCE = 'MG_Blog::edit_post';

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
        $resultPage->getConfig()->getTitle()->prepend(__("Edit post"));
        return $resultPage;
    }
}
