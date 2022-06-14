<?php

/**
 * @category    TPH
 * @package     Tph_Onlinedesign
 *
 */
namespace Tph\Onlinedesign\Controller\Index;

/**
 * Class Canva
 *
 * @package Tph\Onlinedesign\Controller\Index
 */
class Canva extends \Magento\Framework\App\Action\Action
{
	/**
	 * @var \Magento\Framework\View\Result\PageFactory
	 */
	protected $_pageFactory;

	/**
	 * Canva constructor.
	 *
	 * @param \Magento\Framework\App\Action\Context $context
	 * @param \Magento\Framework\View\Result\PageFactory $pageFactory
	 */
	public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $pageFactory)
	{
		$this->_pageFactory = $pageFactory;
		return parent::__construct($context);
	}

	/**
	 * @return \Magento\Framework\View\Result\Page
	 */
	public function execute()
	{
		$page = $this->_pageFactory->create();
        return $page;

	}
}