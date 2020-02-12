<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MG\Blog\Controller\Adminhtml\Tag;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Ui\Component\MassAction\Filter;

/**
 * Class MassDelete
 */
class MassDelete extends \Magento\Backend\App\Action implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'MG_Blog::mass_delete_tag';

    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var \MG\Blog\Model\ResourceModel\Post\CollectionFactory
     */
    protected $tagCollectionFactory;
    /**
     * @param Context $context
     * @param Filter $filter
     * @param \MG\Blog\Model\ResourceModel\Post\CollectionFactory $tagCollectionFactory
     */

    protected $tagRepository;

    public function __construct(
        Context $context,
        Filter $filter,
        \MG\Blog\Model\ResourceModel\Tag\CollectionFactory $tagCollectionFactory,
        \MG\Blog\Model\TagRepository $tagRepository
    ) {
        $this->filter = $filter;
        $this->tagCollectionFactory = $tagCollectionFactory;
        $this->tagRepository = $tagRepository;
        parent::__construct($context);
    }

    public function execute()
    {
        $collection = $this->filter->getCollection($this->tagCollectionFactory->create());
        $collectionSize = $collection->getSize();

        foreach ($collection as $tag) {
            $this->tagRepository->delete($tag);
        }

        $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been deleted.', $collectionSize));

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/listing/');
    }
}
