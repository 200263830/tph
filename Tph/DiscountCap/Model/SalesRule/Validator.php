<?php
namespace Tph\DiscountCap\Model\SalesRule;

use Magento\Quote\Model\Quote\Address;
use Magento\SalesRule\Model\Rule;
use Magento\Quote\Model\Quote\Item\AbstractItem;

/**
 * Class Validator
 * @package Tph\DiscountCap\Model\SalesRule
 */
class Validator extends \Magento\SalesRule\Model\Validator
{
    /**
     * Get rules collection for current object state
     *
     * @param Address|null $address
     * @return \Magento\SalesRule\Model\ResourceModel\Rule\Collection
     * @throws \Zend_Db_Select_Exception
     */
    protected function _getRules(Address $address = null)
    {
        parent::_getRules($address);
        $addressId = $this->getAddressId($address);
        $key = $this->getWebsiteId() . '_'
            . $this->getCustomerGroupId() . '_'
            . $this->getCouponCode() . '_'
            . $addressId;
        $appliedRule = $this->getData('applied_rule');
        if($appliedRule && count($this->_rules) && $this->_rules[$key]->count()) {
            $rules = $this->_rules[$key];
            foreach ($rules  as $rule) {
                if ($rule->getId() == $appliedRule->getId()) {
                    $rule->addData([
                        'simple_action' => \Magento\SalesRule\Model\Rule::CART_FIXED_ACTION,
                        'discount_amount' => 50.0000
                    ]);
                }
            }
            $this->_rules[$key] = $rules;
        }
        return $this->_rules[$key];
    }


}
