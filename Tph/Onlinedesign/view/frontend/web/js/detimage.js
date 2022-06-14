define([
    'jquery',
    'jquery/ui',
    'jquery/validate',
    'mage/translate',
    'mage/mage',
    'domReady!'
], function ($) {
    'use strict';
    return function (config) {
       $(".product-custom-option").on('change', function () {
           if(jQuery('.Orientation span').length){
            var k = $('.product-custom-option.admin__control-select').val();
            var hwo = config.category['category'][k];
            if(hwo != undefined){
             hwo = hwo.split("_");
               var parsedValue = hwo[hwo.length - 1];
                hwo = parsedValue.split('x'); 
                if(!isNaN(hwo[0]) && !isNaN(hwo[0])){
                if(hwo[0] % 1 !== 0 || hwo[1] % 1 !== 0){
                    console.log('It is float');
                    hwo[0] = hwo[0]*2;
                    hwo[1] = hwo[1]*2;
                    }
                  jQuery('.product-custom-option').attr('data-crop',Math.floor(parseInt(hwo[0]))+':'+Math.floor(parseInt(hwo[1])));  
                }
                
                
            }
            }
        });

        if(!$('.swatch-attribute.size').length && !jQuery('.Orientation span').length){
            
            var hws = $('#upload_option').attr('data-canva-size');
            if(hws != undefined && hws != ""){
             hws = hws.split("_");
               var parsedValue = hws[hws.length - 1];
                hws = parsedValue.split('x'); 
                
                if(hws[0] % 1 !== 0 || hws[0] % 1 !== 0){
                    console.log('It is float');
                    hws[0] = hws[0]*2;
                    hws[1] = hws[1]*2;
                }
                //console.log(hws);
                jQuery('.product-custom-option').attr('data-crop',Math.floor(parseInt(hws[0]))+':'+Math.floor(parseInt(hws[1])));
            }
        }

        if(config.filename!=0){
            jQuery('.input-text.product-custom-option.Design').attr('value',config.filename);
        }
        setTimeout(
                function () {
                    if (config.size != 0) {

                        if (jQuery('[aria-label="' + config.size + ' Inches' + '"]').length > 0) {
                            jQuery(".swatch-attribute.size .swatch-option").not('[aria-label="' + config.size + ' Inches' + '"]').hide();
                            var oi = jQuery('[aria-label="' + config.size + ' Inches' + '"]').data('option-id');
                            var ai = jQuery('.swatch-attribute.size').data('attribute-id');
                            jQuery('#option-label-size-' + ai + '-item-' + oi).trigger('click');
                        } else {
                            var size = config.size.split("x");
                            console.log('[aria-label="' + size[1] + 'x' + size[0] + ' Inches' + '"]');
                            if (jQuery('[aria-label="' + size[1] + 'x' + size[0] + ' Inches' + '"]').length > 0) {
                                var oi = jQuery('[aria-label="' + size[1] + 'x' + size[0] + ' Inches' + '"]').data('option-id');
                                jQuery(".swatch-attribute.size .swatch-option").not('[aria-label="' + size[1] + 'x' + size[0] + ' Inches' + '"]').hide();
                                var ai = jQuery('.swatch-attribute.size').data('attribute-id');
                                jQuery('[name="super_attribute[' + ai + ']').val(oi);
                                jQuery('#option-label-size-' + ai + '-item-' + oi).trigger('click');
                            }
                        }

                    }
                }, 1000);

        

        jQuery(document).ready(checkContainer);
        function checkContainer() {

            if (jQuery('.fotorama__img').is(':visible')) { //if the container is visible on the page
                if (config.image != 0) {
                    console.log(config.image);
                    jQuery('.fotorama__active .fotorama__img').attr('src', config.image);
                }
            } else {
                setTimeout(checkContainer, 10); //wait 50 ms, then try again
            }
        }

        //Code for form validation 


        const addtocart = (jQuery('#product-addtocart-button').length) ? document.querySelector("#product-addtocart-button") : document.querySelector("#product-updatecart-button");
        // if(jQuery('#product-addtocart-button').length){
        addtocart.onclick = () =>
        {
          //jQuery('#product_addtocart_form').validation();
          var formvalid = jQuery('#product_addtocart_form').mage('validation', {});
           if (formvalid.validation('isValid')) {
            if(jQuery("#product-canva-image").length && jQuery("#upload_option")){
            if (jQuery("#product-canva-image").attr('value') != "" || jQuery("#upload_option").attr('value') != "") {
                     setTimeout(function(){
                        window.location.reload();
                     },4000);
                    }
                 }
             }

            if ($("#upload_option").length == 0) {
                //console.log(1);
                if($("#canva-catalogue").length){
                $("#product-canva-image").addClass("product-canva-option-required");
                $.validator.addMethod(
                        'product-canva-option-required', function (value) {
                            if (value)
                                return true;
                            else
                                return false;
                        }, $.mage.__('Please select/upload Your file!'));
               }
            } else {
               // console.log(2);
                if (jQuery("#product-canva-image").attr('value') == "") {
                    if (jQuery("#upload_option").attr('value') == "") {
                        jQuery("#upload_option").addClass("product-custom-option-required");
                    }
                }
            }
        }


        var canva_image = jQuery("#product-canva-image").val();
        if (!canva_image) {
            jQuery("#upload_option").addClass("product-custom-option-required");
            $("#product-canva-image").addClass("product-canva-option-required")
        } else {
            jQuery("#upload_option").removeClass("product-custom-option-required");
            $("#product-canva-image").removeClass("product-canva-option-required");
        }

        /** Form Validation on click on uploadcare button **/ 
        jQuery(".field.file").click(function(event) {
           jQuery("#upload_option").removeClass("product-custom-option-required");
           var formvalid =  jQuery('#product_addtocart_form').validation();
           console.log(formvalid.validation('isValid'));
            if (!formvalid.validation('isValid')) {
                jQuery('.uploadcare--dialog_status_active').remove();
            }
        });



    };

    setTimeout(function(){
        jQuery(".swatch-option.text").not('.selected').hide();
     },1000);
   

});