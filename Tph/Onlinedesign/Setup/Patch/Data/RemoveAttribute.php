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


/**
 * Class CanvaAttribute
 * @package Tph\Onlinedesign\Setup\Patch\Data
 */
class RemoveAttribute implements DataPatchInterface
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
     * CanvaAttribute constructor.
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

        $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'product_design_dimensions');

         $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'design_dimensions_height'); 

         $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'design_dimensions_width');

         $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'product_list');

        $designDimension = 'Tph\Onlinedesign\Model\Config\Source\DesignDimension';

        $statusOptions = 'Tph\Onlinedesign\Model\Config\Source\ProductList';

         //Tph/Onlinedesign/Setup/Patch/Data/AddProductList.php

        //Tph/Onlinedesign/Setup/Patch/Data/CanvaAttribute.php

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
