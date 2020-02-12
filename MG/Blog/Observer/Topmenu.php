<?php


namespace MG\Blog\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Data\Tree\Node;
use Magento\Framework\Event\ObserverInterface;


class Topmenu implements ObserverInterface
{
    protected $categoryBlock;

    public function __construct(
        \MG\Blog\Block\Category $categoryBlock
    )
    {
        $this->categoryBlock = $categoryBlock;
    }
    /**
     * @param EventObserver $observer
     * @return $this
     */
    public function execute(EventObserver $observer)
    {
        /** @var \Magento\Framework\Data\Tree\Node $menu */
        $menu = $observer->getMenu();
        $tree = $menu->getTree();
        $data = [
            'name'      => "Blog",
            'id'        => '1',
            'url'       => $this->categoryBlock->getBlogUrl(),
        ];
        $node = new Node($data, 'id', $tree, $menu);
        $menu->addChild($node);
        return $this;
    }
}