<?php

namespace MG\Blog\Model\Source;

class Products implements \Magento\Framework\Option\ArrayInterface
{
    protected $productCollectionFactory;
    protected $productRepository;

    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\ProductRepository $productRepository
    ) {
        $this->productRepository = $productRepository;
        $this->productCollectionFactory = $productCollectionFactory;
    }

    public function toOptionArray()
    {
        $collection = $this->productCollectionFactory->create()
            ->addAttributeToFilter('visibility', \Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH);

        $options = [];

        foreach ($collection as $product) {
            $name = $this->productRepository->getById($product->getId())->getName();

            $options[] = ['label' => $name, 'value' => $product->getId()];
        }

        return $options;
    }
}
