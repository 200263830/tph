<?php
/**
 * @category   Tph
 * @package    Tph_Onlinedesign
 * 
 */

namespace Tph\Onlinedesign\Block\Adminhtml\Designid\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;

class Main extends Generic implements TabInterface
{
    protected $_wysiwygConfig;
 
    public function __construct(
        \Magento\Backend\Block\Template\Context $context, 
        \Magento\Framework\Registry $registry, 
        \Magento\Framework\Data\FormFactory $formFactory,  
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        \Tph\Onlinedesign\Model\Config\Source\ProductList $source,
        array $data = []
    ) 
    {
        $this->_wysiwygConfig = $wysiwygConfig;
        $this->source = $source; 
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getTabLabel()
    {
        return __('Landing Page Information');
    }

    /**
     * {@inheritdoc}
     */
    public function getTabTitle()
    {
        return __('Page Information');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Prepare form before rendering HTML
     *
     * @return $this
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('current_tph_onlinedesign_designid');
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('item_');
        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Item Information')]);
        if ($model->getId()) {
            $fieldset->addField('id', 'hidden', ['name' => 'id']);
        }

        $fieldset->addField(
            'design_id',
            'text',
            ['name' => 'design_id', 'label' => __('Design Id'), 'title' => __('Design Id'), 'required' => true]
        );
       
       $fieldset->addField(
                'product_id',
                'select',
                [
                    'name' => 'product_id',
                    'label' => __('Associate to Website'),
                    'title' => __('Associate to Website'),
                    'required' => true,
                    'values' => $this->source->getAllOptions(),

            ]
        );
        
       
        
        $form->setValues($model->getData());
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
