<?php

namespace MG\Blog\Model\Source;

class Tags implements \Magento\Framework\Option\ArrayInterface
{
    protected $tagCollectionFactory;
    protected $tagRepository;

    public function __construct(
        \MG\Blog\Model\ResourceModel\Tag\CollectionFactory $tagCollectionFactory,
        \MG\Blog\Model\TagRepository $tagRepository
    ) {
        $this->tagRepository = $tagRepository;
        $this->tagCollectionFactory = $tagCollectionFactory;
    }

    public function toOptionArray()
    {
        $collection = $this->tagCollectionFactory->create();

        $options = [];

        foreach ($collection as $tag) {
            $name = $this->tagRepository->getById($tag->getId())->getName();

            $options[] = ['label' => $name, 'value' => $tag->getId()];
        }

        return $options;
    }
}
