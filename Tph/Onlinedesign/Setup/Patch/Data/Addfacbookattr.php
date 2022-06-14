<?php

/**
 * @category    TPH
 * @package     Tph_Onlinedesign
 * 
 */


namespace Tph\Onlinedesign\Setup\Patch\Data;


use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Eav\Attribute;
use Magento\Catalog\Model\Category;

/**
 * Class Addfacbookattr
 * @package Tph\Onlinedesign\Setup\Patch\Data
 */
class Addfacbookattr implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * GalleryImageAttribute constructor.
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param EavSetupFactory          $eavSetupFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory          $eavSetupFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);

       
       $eavSetup->addAttribute(
            Product::ENTITY,
            'enable_fb_product',
            [
                'type' => 'int',
                'backend' => '',
                'frontend' => '',
                'label' => 'Facebook Shop Product',
                'input' => 'boolean',
                'class' => '',
                'global' => Attribute::SCOPE_STORE,
                'group' => 'Facebook Shop Product',
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => 0,
                'searchable' => false,
                'filterable' => false,
                'filterable_in_search' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'unique' => false,
                'apply_to' => ''
            ]
        );

        $eavSetup->addAttribute(
            Product::ENTITY,
            'product_condition',
            [
                'type' => 'text',
                'backend' => '',
                'frontend' => '',
                'label' => 'Product Condition',
                'input' => 'select',
                'class' => '',
                'source' => 'Meetanshi\FaceBookShop\Model\Config\Source\FbConditions',
                'global' => Attribute::SCOPE_STORE,
                'group' => 'Facebook Shop Product',
                'visible' => true,
                'required' => true,
                'user_defined' => true,
                'default' => 'new',
                'searchable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'filterable' => false,
                'filterable_in_search' => false,
                'used_in_product_listing' => true,
                'unique' => false,
                'apply_to' => ''
            ]
        );

        $eavSetup->addAttribute(
            Product::ENTITY,
            'google_product_category',
            [
                'type' => 'text',
                'backend' => '',
                'frontend' => '',
                'label' => 'Google Product Category',
                'input' => 'text',
                'class' => '',
                'global' => Attribute::SCOPE_STORE,
                'group' => 'Facebook Shop Product',
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'filterable' => false,
                'filterable_in_search' => false,
                'searchable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'unique' => false,
                'apply_to' => '',
                'note' => 'Set the category of your item based on the Google product taxonomy - https://support.google.com/merchants/answer/6324436'
            ]
        );

        $eavSetup->addAttribute(
            Category::ENTITY,
            'google_product_category',
            [
                'group' => 'Facebook Shop Integration',
                'type' => 'text',
                'label' => 'Google Product Category',
                'input' => 'text',
                'required' => false,
                'sort_order' => 100,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'visible' => true,
                'user_defined' => true,
                'backend' => ''
            ]
        );

    }

    /**  
     * {@inheritdoc}
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        return [];
    }

}
