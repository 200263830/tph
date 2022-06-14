<?php

/**
 * @category    TPH
 * @package     Tph_Onlinedesign
 * @description Product attribute for Size chart 
 */


namespace Tph\Onlinedesign\Setup\Patch\Data;


use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;


/**
 * Class SizeProductAttribute
 * @package Tph\Onlinedesign\Setup\Patch\Data
 */
class SizeProductAttribute implements DataPatchInterface
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

        $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'size_chart_product');
        
        
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'size_chart_product',
            [
                'group' => 'Canva Product Attribute',
                'type' => 'varchar',
                'backend' => '',
                'frontend' => '',
                'label' => 'Size chart',
                'input' => 'text',
                'class' => '',
                'source' => '',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'is_used_in_grid' => true,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'unique' => false,
                'note' => 'Add the custom block ex: {{block class="Magento\\Cms\\Block\\Block" block_id="block_identifier"}}',
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