function onDesignPublishCallback(options) {
    console.log(options);
    var exportUrl = options.exportUrl;
    var designId = options.designId;
    var baseurl = jQuery('#canva-design-button').data("url");
    var productType = jQuery('#canva-design-button').data("product-type");
    var designType = jQuery('#canva-design-button').attr('data-design-type');
    var edit = jQuery('#canva-design-button').data("edit");
    var qty = jQuery('#qty').val();
    var productId = jQuery('#canva-design-button').data("productid");
    productId = (edit) ? jQuery('.getProductId').data("productid") : productId;
    var options = '';
    var prodyctIdc = '';
    var paretpid = '';
    if (productType == 'configurable') {
        options = selectedProduct();
        if(!edit) {
            paretpid =  productId;
            productId = jQuery('[data-role="swatch-options"]').data('mageSwatchRenderer').getProduct();
            
        }
    } else if (productType == 'bundle') {
        options = selectedProductBundle();
    } else if (productType == 'grouped') {
        options = GroupedProductQuantity();
    }
    var option_qty = (productType == 'bundle') ? BundleProductQuantity() : '';
    
    designType = (designType!="" && designType!=undefined)?designType:0;
    jQuery.ajax({
        showLoader: true,
        url: baseurl + 'onlinedesign/index/index',
        data: {
            exportUrl: exportUrl,
            designId: designId,
            productId: productId,
            paretpid:paretpid,
            designType: designType,
            edit: edit,
            productType: productType,
            detailpage: 1,
        },
        type: "POST",
        dataType: 'json',

    }).done(function (data) {
        console.log(edit);
        if (edit==1) {
            jQuery('[data-edit]').removeClass('getProductId');
            jQuery('.product-image-container-' + data.product_id).find('.product-image-photo').attr('src', data.media_url);
            window.location.reload();
        } else {
            //window.location.href = baseurl + "checkout/cart/";
            jQuery('#canva-design-button').removeAttr("data-design-type");
            jQuery('#canva-design-button').attr('data-design-id', data.designId);
            jQuery('span #canva-design-button').html('Edit Design<span class="browse-temp">Canva\'s design tools let you enjoy designing without the fuss</span>');
            jQuery('#product-canva-image').attr("data-product-canva-image",data.media_url);
            jQuery("#product-canva-image").val(data.media_url);
            jQuery('#product-canva-image').attr("value",data.media_url);
            jQuery('#upload_option').val("");
            var hrefurl= data.media_url;
            var last_part = hrefurl.substr(hrefurl.lastIndexOf('/') + 1)
            jQuery('.input-text.product-custom-option.Design').attr("value",last_part);
            jQuery(document).ready(checkContainer);

            function checkContainer () {
              if(jQuery('.fotorama__img').is(':visible')){ //if the container is visible on the page
                if(data.media_url) {
                    jQuery('.fotorama__active .fotorama__img').attr('src', data.media_url);
                }
              } else {
                setTimeout(checkContainer, 5); //wait 50 ms, then try again
              }
            }

            if(jQuery('#product-updatecart-button').length){
                jQuery("input[name='custom_image']").attr("value",last_part);
                jQuery("input[name='designid']").attr("value",designId);
            }

        }
    });
};

function selectedProduct() {
    var selected_options = {};
    jQuery('div.swatch-attribute').each(function (k, v) {
        var attribute_id = jQuery(v).attr('data-attribute-id');
        var option_selected = jQuery(v).attr('data-option-selected');
        if (!attribute_id || !option_selected) {
            return;
        }
        selected_options[attribute_id] = option_selected;
    });
    return selected_options;
}

function selectedProductBundle() {
    var selected_options = {};
    var x = 1;
    jQuery('.bundle.option:checked,input:hidden.bundle.option:checked').each(function (k, v) {

        var attribute_id = jQuery(v).val();
        selected_options[x] = attribute_id;
        x++;
    });
    return selected_options;
}

function BundleProductQuantity() {
    var selected_options = {};
    var x = 1;
    jQuery('.qty-holder input.qty').each(function (k, v) {
        var attribute_id = jQuery(v).val();
        selected_options[x] = attribute_id;
        x++;
    });
    return selected_options;
}

function GroupedProductQuantity() {
    var selected_options = {};
    var x = 1;
    jQuery('.control.qty input.qty').each(function (k, v) {
        var attribute_id = jQuery(v).attr('data-selector').replace(/\D/g, '');
        var option_selected = jQuery(v).val();
        selected_options[attribute_id] = option_selected;
        x++;
    });
    return selected_options;
}

define([
    "jquery",
    "mage/validation"
], function ($) {
    "use strict";
    $.widget('magently.ajax', {
        options: {
            method: 'post',
            triggerEvent: 'click'
        },

        _create: function () {
            if(jQuery('#product-updatecart-button').length){
            //Edit 2 Means update the cart item 
            jQuery("#canva-design-button").attr("data-edit","2");
            jQuery('#canva-design-button').removeAttr("data-design-type");
            jQuery('#canva-design-button').attr('data-design-id', jQuery("input[name='designid']").val());
            jQuery('span #canva-design-button').html('Edit Design<span class="browse-temp">Canva\'s design tools let you enjoy designing without the fuss</span>');
            }
            this._bind();
            this._form_valid();
            (function (c, a, n) {
                var w = c.createElement(a), s = c.getElementsByTagName(a)[0];
                w.src = n;
                s.parentNode.insertBefore(w, s);
            })(document, 'script', 'https://sdk.canva.com/designbutton/v2/api.js');

            $('[data-edit]').click(function () {
                $(this).addClass('getProductId');
            })

            setTimeout(function () {
                var productId = jQuery('#canva-design-button').data("productid");
                var dispalyImage = jQuery('#canva-design-button').data("canva-"+productId);
                if(!dispalyImage=="") {
                    jQuery('.fotorama__active .fotorama__img').attr('src', dispalyImage);
                }

            }, 500);

            const addtocart = document.querySelector("#product-addtocart-button");
            if(jQuery('#product-addtocart-button').length){
            addtocart.onclick = () =>
            {
                var canva_image = jQuery("#product-canva-image").val();
                if(!canva_image){
                    jQuery("#upload_option").addClass("product-custom-option-required");
                }else{
                    jQuery("#upload_option").removeClass("product-custom-option-required");
                }
                
            };
            }

        },

        _bind: function () {
            var self = this;
        },

        _form_valid: function (e) {
            var dataForm = $('#product_addtocart_form');
            var ignore = null;

            dataForm.mage('validation', {
                ignore: ignore ? ':hidden:not(' + ignore + ')' : ':hidden'
            }).find('input:text').attr('autocomplete', 'off');

             $('#canva-design-button').click(function (e) { //can be replaced with any event
                $("#upload_option").removeClass("product-custom-option-required");
                $("#product-canva-image").removeClass("product-canva-option-required");
                
                var formvalid = dataForm.validation('isValid');
                if (!formvalid) {
                    e.stopImmediatePropagation();
                }
            });

        }
    });
    return $.magently.ajax;
});

define([
    'Magento_Customer/js/customer-data'
    ], function (customerData) {
    return function () {
        var sections = ['cart'];
        customerData.invalidate(sections);
        customerData.reload(sections, true);
    }
});


