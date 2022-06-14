<?php

namespace Tph\Core\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Request\Http;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Helper\AbstractHelper;


class Data extends AbstractHelper
{

    /**
     * @var Http
     */
    private $request;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * Data constructor.
     *
     * @param Context $context
     * @param Http $request
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Context $context,
        Http $request,
        StoreManagerInterface $storeManager
    ) {
        $this->request          = $request;
        $this->storeManager     = $storeManager;
        parent::__construct($context);
    }

    /**
     * @return int
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCurrentStoreId()
    {
        $storeId = $this->request->getParam('store_id');
        if ($storeId) {
            $result = $storeId;
        } else {
            $result = $this->storeManager->getStore()->getId();
        }
        return $result;
    }

    /**
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getStoreBaseUrl()
    {
        return $this->storeManager->getStore()->getBaseUrl();
    }
}
