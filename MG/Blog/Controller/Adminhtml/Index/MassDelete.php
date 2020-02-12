<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MG\Blog\Controller\Adminhtml\Index;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Ui\Component\MassAction\Filter;

/**
 * Class MassDelete
 */
class MassDelete extends \Magento\Backend\App\Action implements HttpPostActionInterface
{
    const ADMIN_RESOURCE = 'MG_Blog::mass_delete_post';

    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var CollectionFactory
     */
    protected $postCollectionFactory;
    protected $postCategoryCollectionFactory;
    protected $postTagCollectionFactory;
    protected $postImageCollectionFactory;
    protected $postProductCollectionFactory;

    /**
     * @param Context $context
     * @param Filter $filter
     * @param \MG\Blog\Model\ResourceModel\Post\CollectionFactory $postCollectionFactory
     * @param \MG\Blog\Model\ResourceModel\PostCategory\CollectionFactory $postCategoryCollectionFactory
     * @param \MG\Blog\Model\ResourceModel\PostTag\CollectionFactory $postTagCollectionFactory
     * @param \MG\Blog\Model\ResourceModel\PostImage\CollectionFactory $postImageCollectionFactory
     * @param \MG\Blog\Model\ResourceModel\PostProduct\CollectionFactory $postProductCollectionFactory
     */
    public function __construct(
        Context $context,
        Filter $filter,
        \MG\Blog\Model\ResourceModel\Post\CollectionFactory $postCollectionFactory,
        \MG\Blog\Model\ResourceModel\PostCategory\CollectionFactory $postCategoryCollectionFactory,
        \MG\Blog\Model\ResourceModel\PostTag\CollectionFactory $postTagCollectionFactory,
        \MG\Blog\Model\ResourceModel\PostImage\CollectionFactory $postImageCollectionFactory,
        \MG\Blog\Model\ResourceModel\PostProduct\CollectionFactory $postProductCollectionFactory
    ) {
        $this->postProductCollectionFactory = $postProductCollectionFactory;
        $this->postImageCollectionFactory = $postImageCollectionFactory;
        $this->postTagCollectionFactory = $postTagCollectionFactory;
        $this->filter = $filter;
        $this->postCollectionFactory = $postCollectionFactory;
        $this->postCategoryCollectionFactory = $postCategoryCollectionFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $collection = $this->filter->getCollection($this->postCollectionFactory->create());
        $collectionSize = $collection->getSize();

        foreach ($collection as $post) {
            $postCategoryCollection = $this->postCategoryCollectionFactory
                ->create()
                ->addFieldToFilter("post_id", ['eq' => $post->getId()]);

            foreach ($postCategoryCollection as $pcc) {
                $pcc->delete();
            }

            $postTagCollection = $this->postTagCollectionFactory
                ->create()
                ->addFieldToFilter("post_id", ['eq' => $post->getPostId()]);

            foreach ($postTagCollection as $ptc) {
                $ptc->delete();
            }

            $postProductCollection = $this->postProductCollectionFactory
                ->create()
                ->addFieldToFilter("post_id", ['eq' => $post->getPostId()]);

            foreach ($postProductCollection as $ppc) {
                $ppc->delete();
            }

            $postImageCollection = $this->postImageCollectionFactory
                ->create()
                ->addFieldToFilter("post_id", ['eq' => $post->getPostId()]);

            foreach ($postImageCollection as $pic) {
                $pic->delete();
            }

            $post->delete();
        }

        $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been deleted.', $collectionSize));

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}
