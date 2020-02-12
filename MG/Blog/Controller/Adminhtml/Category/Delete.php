<?php

namespace MG\Blog\Controller\Adminhtml\Category;

use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Setup\Exception;

class Delete extends \Magento\Backend\App\Action
{
    const ADMIN_RESOURCE = "MG_Blog::delete_category";

    protected $resultPageFactory;
    protected $categoryRepository;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \MG\Blog\Model\TagRepository $categoryRepository
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');

        try {
            $category = $this->categoryRepository->getById($id);
            $this->categoryRepository->delete($category);
            $this->messageManager->addSuccess(__('Your category has been deleted !'));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Error while trying to delete category.'));
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('*/*/');
        }

        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*/listing/');
    }
}
