<?php

namespace MG\Blog\Controller\Adminhtml\Tag;

use Magento\Framework\Exception\LocalizedException;

class Save extends \Magento\Backend\App\Action
{
    const ADMIN_RESOURCE = "MG_Blog::save_tag";

    protected $resultPageFactory;
    protected $tagFactory;
    protected $tagRepository;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \MG\Blog\Model\TagFactory $tagFactory,
        \MG\Blog\Model\TagRepository $tagRepository
    ) {
        $this->tagRepository = $tagRepository;
        $this->resultPageFactory = $resultPageFactory;
        $this->tagFactory = $tagFactory;

        parent::__construct($context);
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            try {
                $id = $this->getRequest()->getParam('id');
                $tag = $this->tagFactory->create();

                if ($id) {
                    try {
                        $tag = $this->tagRepository->getById($id);
                    } catch (LocalizedException $e) {
                        $this->messageManager->addErrorMessage(__('This tag no longer exists.'));
                        return $resultRedirect->setPath('*/*/');
                    }
                }

                $data = array_filter($data, function ($value) {
                    return $value !== '';
                });

                $tag->setData($data);

                $this->tagRepository->save($tag);

                $this->messageManager->addSuccess(__('Successfully saved the item.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
                return $resultRedirect->setPath('*/*/listing/');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData($data);
                return $resultRedirect->setPath('*/*/edit', ['id' => $tag->getId()]);
            }
        }

        return $resultRedirect->setPath('*/*/listing/');
    }
}
