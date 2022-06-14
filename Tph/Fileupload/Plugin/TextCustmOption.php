<?php

namespace Tph\Fileupload\Plugin;


class TextCustmOption
{
    public function beforeSetTemplate(
        \Magento\Catalog\Block\Product\View\Options\Type\Text $subject,
        $template
    ) 
    {
         return ['Tph_Fileupload::product/view/options/type/text.phtml'];
    }
}