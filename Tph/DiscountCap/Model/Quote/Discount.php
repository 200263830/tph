<?php
namespace Tph\DiscountCap\Model\Quote;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Quote\Api\Data\AddressInterface;
use Magento\Quote\Model\Quote\Item\AbstractItem;
use Magento\SalesRule\Api\Data\DiscountDataInterface;
use Magento\SalesRule\Api\Data\DiscountDataInterfaceFactory;
use Magento\SalesRule\Api\Data\RuleDiscountInterfaceFactory;
use Magento\SalesRule\Model\Data\RuleDiscount;
use Magento\SalesRule\Model\Validator;
use Magento\Store\Model\StoreManagerInterface;
use Magento\SalesRule\Model\ResourceModel\Rule\CollectionFactory;

/**
 * Class Discount
 * @package Tph\DiscountCap\Model\Quote
 */
class Discount extends \Magento\SalesRule\Model\Quote\Discount
{

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;
    /**
     * @var \Magento\SalesRule\Model\CouponFactory
     */
    private $couponFactory;

    /**
     * @var RuleDiscountInterfaceFactory
     */
    protected $discountInterfaceFactory;
    /**
     * @var DiscountDataInterfaceFactory
     */
    protected $discountDataInterfaceFactory;

    /**
     * Discount constructor.
     * @param ManagerInterface $eventManager
     * @param StoreManagerInterface $storeManager
     * @param Validator $validator
     * @param PriceCurrencyInterface $priceCurrency
     * @param RuleDiscountInterfaceFactory|null $discountInterfaceFactory
     * @param DiscountDataInterfaceFactory|null $discountDataInterfaceFactory
     * @param CollectionFactory $collectionFactory
     * @param \Magento\SalesRule\Model\CouponFactory $couponFactory
     * @param \Tph\DiscountCap\Model\SalesRule\Validator $tphValidator
     */
    public function __construct(
        ManagerInterface $eventManager,
        StoreManagerInterface $storeManager,
        Validator $validator,
        PriceCurrencyInterface $priceCurrency,
        RuleDiscountInterfaceFactory $discountInterfaceFactory = null,
        DiscountDataInterfaceFactory $discountDataInterfaceFactory = null,
        CollectionFactory $collectionFactory,
        \Magento\SalesRule\Model\CouponFactory $couponFactory
    ) {
        parent::__construct($eventManager, $storeManager, $validator, $priceCurrency, $discountInterfaceFactory, $discountDataInterfaceFactory);
        $this->discountInterfaceFactory = $discountInterfaceFactory
            ?: ObjectManager::getInstance()->get(RuleDiscountInterfaceFactory::class);

        $this->discountDataInterfaceFactory = $discountDataInterfaceFactory
            ?: ObjectManager::getInstance()->get(DiscountDataInterfaceFactory::class);
        $this->collectionFactory = $collectionFactory;
        $this->couponFactory = $couponFactory;
    }

    /**
     * @param \Magento\Quote\Model\Quote $quote
     * @param \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment
     * @param \Magento\Quote\Model\Quote\Address\Total $total
     * @return $this|\Magento\SalesRule\Model\Quote\Discount
     */

    public function collect(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment,
        \Magento\Quote\Model\Quote\Address\Total $total
    ) {
        parent::collect($quote, $shippingAssignment, $total);

        $address = $shippingAssignment->getShipping()->getAddress();
        $items = $shippingAssignment->getItems();
        if (!count($items)) {
            return $this;
        }

        if ($quote->getCouponCode()) {
            $couponCode = $quote->getCouponCode();
            $coupon = $this->couponFactory->create();
            $coupon->load($couponCode, 'code');
            $ruleId = $coupon->getRuleId();
            $rule = $this->collectionFactory->create()
                ->addFieldToFilter('rule_id', ['eq' => $ruleId])->getFirstItem();

            if($rule->getDiscountCap() && $rule->getDiscountCap() < abs($total->getDiscountAmount())) {
                $this->updateDiscount($quote, $items, $address, $total, $rule);
            }

        }
        return $this;
    }

    /**
     * @param $quote
     * @param $items
     * @param $address
     * @param $total
     * @param $rule
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Zend_Db_Select_Exception
     * @throws \Zend_Validate_Exception
     */
    protected function updateDiscount($quote, $items, $address, $total, $rule)
    {
        $total->setTotalAmount('discount', 0)
            ->setBaseTotalAmount('discount',0);

        $this->calculator->reset($address);
        $this->calculator->setData('applied_rule', $rule);

        $store = $this->storeManager->getStore($quote->getStoreId());

        $eventArgs = [
            'website_id' => $store->getWebsiteId(),
            'customer_group_id' => $quote->getCustomerGroupId(),
            'coupon_code' => $quote->getCouponCode(),
        ];
        $address->setDiscountAmount(0);

        $this->calculator->init($store->getWebsiteId(), $quote->getCustomerGroupId(), $quote->getCouponCode());
        $this->calculator->initTotals($items, $address);

        $address->setDiscountDescription([]);
        $items = $this->calculator->sortItemsByPriority($items, $address);
        $address->getExtensionAttributes()->setDiscounts([]);
        $addressDiscountAggregator = [];

        /** @var Item $item */
        foreach ($items as $item) {
            if ($item->getNoDiscount() || !$this->calculator->canApplyDiscount($item)) {
                $item->setDiscountAmount(0);
                $item->setBaseDiscountAmount(0);

                // ensure my children are zeroed out
                if ($item->getHasChildren() && $item->isChildrenCalculated()) {
                    foreach ($item->getChildren() as $child) {
                        $child->setDiscountAmount(0);
                        $child->setBaseDiscountAmount(0);
                    }
                }
                continue;
            }
            // to determine the child item discount, we calculate the parent
            if ($item->getParentItem()) {
                continue;
            }

            $eventArgs['item'] = $item;
            $this->eventManager->dispatch('sales_quote_address_discount_item', $eventArgs);

            if ($item->getHasChildren() && $item->isChildrenCalculated()) {
                $this->calculator->process($item);
                foreach ($item->getChildren() as $child) {
                    $eventArgs['item'] = $child;
                    $this->eventManager->dispatch('sales_quote_address_discount_item', $eventArgs);
                    $this->aggregateItemDiscount($child, $total);
                }
            } else {
                $this->calculator->process($item);
                $this->aggregateItemDiscount($item, $total);
            }
            if ($item->getExtensionAttributes()) {
                $this->aggregateDiscountPerRule($item, $address, $addressDiscountAggregator);
            }
        }

        $this->calculator->prepareDescription($address);
        $total->setDiscountDescription($address->getDiscountDescription());
        $total->setSubtotalWithDiscount($total->getSubtotal() + $total->getDiscountAmount());
        $total->setBaseSubtotalWithDiscount($total->getBaseSubtotal() + $total->getBaseDiscountAmount());
        $address->setDiscountAmount($total->getDiscountAmount());
        $address->setBaseDiscountAmount($total->getBaseDiscountAmount());
    }

    /**
     * Aggregates discount per rule
     *
     * @param AbstractItem $item
     * @param AddressInterface $address
     * @param array $addressDiscountAggregator
     * @return void
     */
    private function aggregateDiscountPerRule(
        AbstractItem $item,
        AddressInterface $address,
        array &$addressDiscountAggregator
    ) {
        $discountBreakdown = $item->getExtensionAttributes()->getDiscounts();
        if ($discountBreakdown) {
            foreach ($discountBreakdown as $value) {
                /* @var DiscountDataInterface $discount */
                $discount = $value->getDiscountData();
                $ruleLabel = $value->getRuleLabel();
                $ruleID = $value->getRuleID();
                if (isset($addressDiscountAggregator[$ruleID])) {
                    /** @var RuleDiscount $cartDiscount */
                    $cartDiscount = $addressDiscountAggregator[$ruleID];
                    $discountData = $cartDiscount->getDiscountData();
                    $discountData->setBaseAmount($discountData->getBaseAmount()+$discount->getBaseAmount());
                    $discountData->setAmount($discountData->getAmount()+$discount->getAmount());
                    $discountData->setOriginalAmount($discountData->getOriginalAmount()+$discount->getOriginalAmount());
                    $discountData->setBaseOriginalAmount(
                        $discountData->getBaseOriginalAmount()+$discount->getBaseOriginalAmount()
                    );
                } else {
                    $data = [
                        'amount' => $discount->getAmount(),
                        'base_amount' => $discount->getBaseAmount(),
                        'original_amount' => $discount->getOriginalAmount(),
                        'base_original_amount' => $discount->getBaseOriginalAmount()
                    ];
                    $discountData = $this->discountDataInterfaceFactory->create(['data' => $data]);
                    $data = [
                        'discount' => $discountData,
                        'rule' => $ruleLabel,
                        'rule_id' => $ruleID,
                    ];
                    /** @var RuleDiscount $cartDiscount */
                    $cartDiscount = $this->discountInterfaceFactory->create(['data' => $data]);
                    $addressDiscountAggregator[$ruleID] = $cartDiscount;
                }
            }
        }
        $address->getExtensionAttributes()->setDiscounts(array_values($addressDiscountAggregator));
    }

}
