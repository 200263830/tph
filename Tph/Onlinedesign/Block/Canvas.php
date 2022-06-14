<?php

/**
 * @category    TPH
 * @package     Tph_Onlinedesign
 *
 */
namespace Tph\Onlinedesign\Block;

/**
 * Class Canvas
 *
 * @package Tph\Onlinedesign\Block
 */
class Canvas extends \Magento\Framework\View\Element\Template
{
    /**
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context
    ) {
        parent::__construct($context);
    }

    /**
     * Prepare layout before rendering HTML
     *
     * @return string
     */
    public function _prepareLayout()
    {
        return parent::_prepareLayout();
    }
}
