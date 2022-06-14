<?php

/**
 * @category    TPH
 * @package     Tph_Onlinedesign
 *
 */

namespace Tph\Onlinedesign\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Data\Tree\Node;
use Magento\Framework\Data\Tree\NodeFactory;
use Magento\Framework\UrlInterface;

class Topmenu implements ObserverInterface
{
    /**
     * @param NodeFactory $nodeFactory
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        \Tph\Onlinedesign\Helper\Data $helperData,
        UrlInterface $urlBuilder
    ) {
        $this->_helperData = $helperData;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     *
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var \Magento\Framework\Data\Tree\Node $menu */
        $menu = $observer->getMenu();
        $tree = $menu->getTree();
        $url = $this->urlBuilder->getBaseUrl();
        $data = [
            'name'      => __('Canva'),
            'id'        => 'canva',
            'url'       => $url . 'canva',
            'is_active' => false
        ];
        if ($this->_helperData->getModuleEnable()) {
            $node = new Node($data, 'id', $tree, $menu);
            $menu->addChild($node);
        }
        return $this;
    }
}