<?php

namespace Tph\Onlinedesign\Ui\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\CustomOptions as CustomOptionsModifier;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\Component\Form\Element\Checkbox;
use Magento\Ui\Component\Form\Element\DataType\Text;
use Magento\Ui\Component\Form\Field;
use Magento\Ui\Component\Form\Element\Input;

class CustomOptions extends AbstractModifier
{
    protected $meta = [];

    public function __construct(
        UrlInterface $urlBuilder,
        LocatorInterface $locator,
        StoreManagerInterface $storeManager
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->locator = $locator;
        $this->storeManager = $storeManager;
    }

    public function modifyData(array $data)
    {
        return $data;
    }

    public function modifyMeta(array $meta)
    {
        $this->meta = $meta;

        $this->addCustomOptionsFields();

        return $this->meta;
    }

    protected function addCustomOptionsFields()
    {
        $groupCustomOptionsName = CustomOptionsModifier::GROUP_CUSTOM_OPTIONS_NAME;
        $optionContainerName = CustomOptionsModifier::CONTAINER_OPTION;
        $commonOptionContainerName = CustomOptionsModifier::CONTAINER_COMMON_NAME;

        $this->meta[$groupCustomOptionsName]['children']['options']['children']['record']['children']
       [$optionContainerName]['children']['values']['children']['record']['children'] = array_replace_recursive(
           $this->meta[$groupCustomOptionsName]['children']['options']['children']['record']['children']
           [$optionContainerName]['children']['values']['children']['record']['children'],
           $this->getValueFieldsConfig());
       

        // Add fields to the values
       // $this->meta[$groupCustomOptionsName]['children']['options']['children']['record']['children']
       // [$optionContainerName]['children']['values']['children']['record']['children'] = array_replace_recursive(
       //     $this->meta[$groupCustomOptionsName]['children']['options']['children']['record']['children']
       //     [$optionContainerName]['children']['values']['children']['record']['children'],
       //     $this->getHeightFieldsConfig()   
       // );
       
       // Add fields to the values
       // $this->meta[$groupCustomOptionsName]['children']['options']['children']['record']['children']
       // [$optionContainerName]['children']['values']['children']['record']['children'] = array_replace_recursive(
       //     $this->meta[$groupCustomOptionsName]['children']['options']['children']['record']['children']
       //     [$optionContainerName]['children']['values']['children']['record']['children'],
       //     $this->getWidthFieldsConfig()   
       // );
    
    }

    
    /**
    * The custom option fields config
    *
    * @return array
    */
   protected function getValueFieldsConfig()
   {
       $fields['category'] = $this->getDescriptionFieldConfig();

       return $fields;
   }


    /**
    * The custom option fields Height
    *
    * @return array
    */
   protected function getHeightFieldsConfig()
   {
       $fields['height'] = $this->getHeightFieldConfig();

       return $fields;
   }
   
   
   /**
    * The custom option fields width
    *
    * @return array
    */
   protected function getWidthFieldsConfig()
   {
       $fields['width'] = $this->getWidthFieldConfig();

       return $fields;
   }

   /**
    * Get description field config
    *
    * @return array
    */
   protected function getHeightFieldConfig()
   {
       return [
           'arguments' => [
               'data' => [
                   'config' => [
                       'label' => __('Height'),
                       'componentType' => Field::NAME,
                       'formElement'   => Input::NAME,
                       'dataType'      => Text::NAME,
                       'dataScope'     => 'height',
                       'sortOrder'     => 42
                   ],
               ],
           ],
       ];
   }
   
   /**
    * Get description field config
    *
    * @return array
    */
   protected function getWidthFieldConfig()
   {
       return [
           'arguments' => [
               'data' => [
                   'config' => [
                       'label' => __('Width'),
                       'componentType' => Field::NAME,
                       'formElement'   => Input::NAME,
                       'dataType'      => Text::NAME,
                       'dataScope'     => 'width',
                       'sortOrder'     => 43
                   ],
               ],
           ],
       ];
   }

    /**
    * Get description field config
    *
    * @return array
    */
   protected function getDescriptionFieldConfig()
   {
       return [
           'arguments' => [
               'data' => [
                   'config' => [
                       'label' => __('Partner Product Id'),
                       'componentType' => Field::NAME,
                       'formElement'   => Input::NAME,
                       'dataType'      => Text::NAME,
                       'dataScope'     => 'category',
                       'sortOrder'     => 41
                   ],
               ],
           ],
       ];
   }
}