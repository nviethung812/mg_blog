<?php

namespace MG\Blog\Controller\Adminhtml\Index;

use Magento\Framework\Exception\LocalizedException;

class Save extends \Magento\Backend\App\Action
{
    const ADMIN_RESOURCE = 'MG_Blog::save_post';

    protected $resultPageFactory;
    protected $postFactory;
    protected $postCategoryFactory;
    protected $postProductFactory;
    protected $postTagFactory;
    protected $imageUploader;
    protected $postCategoryCollectionFactory;
    protected $postProductCollectionFactory;
    protected $postTagCollectionFactory;
    protected $postRepository;
    protected $postImageFactory;
    protected $postImageCollectionFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \MG\Blog\Model\PostFactory $postFactory,
        \MG\Blog\Model\PostCategoryFactory $postCategoryFactory,
        \MG\Blog\Model\PostTagFactory $postTagFactory,
        \MG\Blog\Model\PostProductFactory $postProductFactory,
        \MG\Blog\Model\PostImageFactory $postImageFactory,
        \MG\Blog\Model\ImageUploader $imageUploader,
        \MG\Blog\Model\ResourceModel\PostCategory\CollectionFactory $postCategoryCollectionFactory,
        \MG\Blog\Model\ResourceModel\PostProduct\CollectionFactory $postProductCollectionFactory,
        \MG\Blog\Model\ResourceModel\PostTag\CollectionFactory $postTagCollectionFactory,
        \MG\Blog\Model\ResourceModel\PostImage\CollectionFactory $postImageCollectionFactory,
        \MG\Blog\Model\PostRepository $postRepository
    ) {
        $this->postImageFactory = $postImageFactory;
        $this->postImageCollectionFactory = $postImageCollectionFactory;
        $this->postRepository = $postRepository;
        $this->postTagFactory = $postTagFactory;
        $this->postTagCollectionFactory = $postTagCollectionFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->postFactory = $postFactory;
        $this->postCategoryFactory = $postCategoryFactory;
        $this->postProductFactory = $postProductFactory;
        $this->imageUploader = $imageUploader;
        $this->postCategoryCollectionFactory = $postCategoryCollectionFactory;
        $this->postProductCollectionFactory  = $postProductCollectionFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $session = $this->_objectManager->get('Magento\Backend\Model\Session');
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            try {
                $post = $this->postFactory->create();
                $id = $data['post_id'];

                $data = array_filter($data, function ($value) {
                    return $value !== '';
                });


                $data = $this->_filterImage($data);
//                var_dump($data);
//                die;
                if ($this->postRepository->isNameExists($data["name"]) && !$id) {
                    $this->messageManager->addErrorMessage(__('Your post name is already exists.'));
                    $session->setFormData($data);
                    return $resultRedirect->setPath('*/*/newAction');
                }

                if ($id) {
                    try {
                        $post = $this->postRepository->getById($id);
                    } catch (LocalizedException $e) {
                        $this->messageManager->addErrorMessage(__('This post no longer exists.'));
                        return $resultRedirect->setPath('*/*/');
                    }
                }

                $post->setData($data);
                $post = $this->postRepository->save($post);

                // Category Handle
                $collection = $this->postCategoryCollectionFactory->create()->addFieldToFilter('post_id', ['eq' => $post->getId()]);
                foreach ($collection as $postCategory) {
                    $postCategory->delete();
                }
                if (isset($data['categories'])) {

                    $categories = $data['categories'];
                    foreach ($categories as $category) {
                        $postCategory = $this->postCategoryFactory->create();
                        $postCategory->setData([
                            'post_id' => $post->getId(),
                            'category_id' => $category
                        ]);
                        $postCategory->save();
                    }
                }

                // Product Handle
                $collection = $this->postProductCollectionFactory->create()->addFieldToFilter('post_id', ['eq' => $post->getId()]);
                foreach ($collection as $postProduct) {
                    $postProduct->delete();
                }
                if (isset($data['products'])) {

                    $products = $data['products'];
                    foreach ($products as $product) {
                        $postProduct = $this->postProductFactory->create();
                        $postProduct->setData([
                            'post_id' => $post->getId(),
                            'product_id' => $product
                        ]);
                        $postProduct->save();
                    }
                }

                // Tag Handle
                $collection = $this->postTagCollectionFactory->create()->addFieldToFilter('post_id', ['eq' => $post->getId()]);
                foreach ($collection as $postTag) {
                    $postTag->delete();
                }
                if (isset($data['tags'])) {
                    $tags = $data['tags'];
                    foreach ($tags as $tag) {
                        $postTag = $this->postTagFactory->create();
                        $postTag->setData([
                            'post_id' => $post->getId(),
                            'tag_id' => $tag
                        ]);
                        $postTag->save();
                    }
                }

                // Gallery Handle
                $collection = $this->postImageCollectionFactory->create()->addFieldToFilter('post_id', ['eq' => $post->getId()]);
                foreach ($collection as $postImage) {
                    $postImage->delete();
                }
                if (isset($data['gallery'])) {

                    $images = $data['gallery'];
                    foreach ($images as $image) {
                        $postImage = $this->postImageFactory->create();
                        $postImage->setData([
                            'post_id' => $post->getId(),
                            'image_name' => $image["name"],
                            'image_size' => $image["size"]
                        ]);
                        $postImage->save();
                    }
                }

                // Thumbnail Handle
                if (isset($data['thumbnail'])) {
                    $this->imageUploader->moveFileFromTmp($data['thumbnail']);
                }

                // Galley Handle
                if (isset($data['gallery'])) {
                    foreach ($data["gallery"] as $image) {
                        $this->imageUploader->moveFileFromTmp($image["name"]);
                    }
                }

                $this->messageManager->addSuccessMessage(__('Successfully saved the item.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData($data);
                return $resultRedirect->setPath('*/*/edit', ['id' => $post->getId()]);
            }
        }

        return $resultRedirect->setPath('*/*/');
    }

    private function _filterImage(array $rawData)
    {
        //Replace thumbnail with fileUploader field name
        $data = $rawData;
        if (isset($data['thumbnail'][0])) {
            $data['thumbnail'] = $data['thumbnail'][0]['name'];
        } else {
            $data['thumbnail'] = '';
        }

        if (isset($data["gallery"])) {
            foreach ($data["gallery"] as $key => $image) {
                $data["gallery"][$key] = [
                    "name" => $image["name"],
                    "size" => $image["size"]
                ];
            }
        } else {
            $data["gallery"] = null;
        }

        return $data;
    }
}
