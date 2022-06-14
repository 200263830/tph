<?php

namespace Tph\DiscountCap\Setup;

use Magento\Framework\App\Config\ConfigResource\ConfigInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

/**
 * Class UpgradeSchema
 * @package Tph\DiscountCap\Setup
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    protected $resourceConfig;

    public function __construct(
        ConfigInterface $resourceConfig
    ) {
        $this->resourceConfig = $resourceConfig;
    }

    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        $installer->getConnection()->addColumn(
            $installer->getTable('salesrule'),
            'discount_cap',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'unsigned' => true,
                'nullable' => true,
                'default' => '',
                'comment' => 'discount_cap'
            ]
        );
        $installer->endSetup();
    }
}
