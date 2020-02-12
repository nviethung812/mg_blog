<?php

namespace MG\Blog\Controller\Adminhtml\Tag;

use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Setup\Exception;

class Delete extends \Magento\Backend\App\Action
{
    const ADMIN_RESOURCE = 'MG_Blog::delete_tag';

    protected $resultPageFactory;
    protected $tagRepository;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \MG\Blog\Model\TagRepository $tagRepository
    ) {
        $this->tagRepository = $tagRepository;
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');

        try {
            $tag = $this->tagRepository->getById($id);
            $this->tagRepository->delete($tag);
            $this->messageManager->addSuccess(__('Your Tag has been deleted !'));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Error while trying to delete Tag.'));
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('*/*/');
        }

        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*/listing/');
    }
}
