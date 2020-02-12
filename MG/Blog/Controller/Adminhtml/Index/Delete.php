<?php

namespace MG\Blog\Controller\Adminhtml\Index;

use Magento\Framework\Exception\NoSuchEntityException;

class Delete extends \Magento\Backend\App\Action
{
    const ADMIN_RESOURCE = 'MG_Blog::delete_post';

    protected $resultPageFactory;
    protected $postCategoryCollectionFactory;
    protected $postTagCollectionFactory;
    protected $postImageCollectionFactory;
    protected $postRepository;
    protected $postProductCollectionFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \MG\Blog\Model\ResourceModel\PostCategory\CollectionFactory $postCategoryCollectionFactory,
        \MG\Blog\Model\ResourceModel\PostTag\CollectionFactory $postTagCollectionFactory,
        \MG\Blog\Model\ResourceModel\PostImage\CollectionFactory $postImageCollectionFactory,
        \MG\Blog\Model\ResourceModel\PostProduct\CollectionFactory $postProductCollectionFactory,
        \MG\Blog\Model\PostRepository $postRepository
    ) {
        $this->postProductCollectionFactory = $postProductCollectionFactory;
        $this->postImageCollectionFactory = $postImageCollectionFactory;
        $this->postRepository = $postRepository;
        $this->postCategoryCollectionFactory = $postCategoryCollectionFactory;
        $this->postTagCollectionFactory = $postTagCollectionFactory;
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $resultRedirect = $this->resultRedirectFactory->create();

        try {
            $post = $this->postRepository->getById($id);
            if (!$post) {
                $this->messageManager->addError(__('Unable to process. please, try again.'));
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }

            try {
                $postCategoryCollection = $this->postCategoryCollectionFactory
                    ->create()
                    ->addFieldToFilter("post_id", ['eq' => $post->getPostId()]);

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
//                var_dump(count($postImageCollection));die;
                foreach ($postImageCollection as $pic) {
                    $pic->delete();
                }

                $post->delete();
                $this->messageManager->addSuccess(__('Your post has been deleted !'));
            } catch (\Exception $e) {
                $this->messageManager->addError(__('Error while trying to delete post'));
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*');
            }
        } catch (NoSuchEntityException $e) {
            $this->messageManager->addErrorMessage(__('This post no longer exists.'));
            return $resultRedirect->setPath('*/*/');
        }


        return $resultRedirect->setPath('*/*');
    }
}
