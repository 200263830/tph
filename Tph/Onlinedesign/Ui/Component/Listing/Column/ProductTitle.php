<?php

namespace Tph\Onlinedesign\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\Api\SearchCriteriaInterface;

class ProductTitle extends \Magento\Ui\Component\Listing\Columns\Column
{

    /**
     * @var SearchCriteriaBuilder
     */
    protected $_searchCriteria;

    /**
     * @var CustomerFactory
     */
    private $customerFactory;

    public function __construct
    (
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        SearchCriteriaInterface $criteria,
        ProductFactory $productFactory,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->_searchCriteria = $criteria;

        $this->productFactory = $productFactory;
    }

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $product = $this->productFactory->create()->load($item['product_id']);
                $item['product_id'] = $product->getName();;
            }
        }
        return $dataSource;
    }
}