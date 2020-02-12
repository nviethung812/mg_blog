<?php

namespace MG\Blog\Controller\Adminhtml\Category;

use Magento\Framework\Exception\LocalizedException;

class Save extends \Magento\Backend\App\Action
{
    const ADMIN_RESOURCE = "MG_Blog::save_category";

    protected $resultPageFactory;
    protected $categoryFactory;
    protected $categoryRepository;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \MG\Blog\Model\CategoryFactory $categoryFactory,
        \MG\Blog\Model\CategoryRepository $categoryRepository
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->resultPageFactory = $resultPageFactory;
        $this->categoryFactory = $categoryFactory;

        parent::__construct($context);
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            try {
                $id = $this->getRequest()->getParam('id');
                $category = $this->categoryFactory->create();

                if ($id) {
                    try {
                        $category = $this->categoryRepository->getById($id);
                    } catch (LocalizedException $e) {
                        $this->messageManager->addErrorMessage(__('This category no longer exists.'));
                        return $resultRedirect->setPath('*/*/');
                    }
                }

                $data = array_filter($data, function ($value) {
                    return $value !== '';
                });

                $category->setData($data);

                $this->categoryRepository->save($category);

                $this->messageManager->addSuccess(__('Successfully saved the item.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
                return $resultRedirect->setPath('*/*/listing/');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData($data);
                return $resultRedirect->setPath('*/*/edit', ['id' => $category->getId()]);
            }
        }

        return $resultRedirect->setPath('*/*/listing/');
    }
}
