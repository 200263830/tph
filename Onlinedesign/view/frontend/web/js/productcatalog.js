/**
 * @category:   Tph
 * @package:    Tph_Onlinedesign
 * @description:  product detail page js canva catalog
 */
define([
    'jquery'
], function ($) {
    'use strict';
    return function (config) {
        $(".product-custom-option").on('change', function () {
            var k = $('.product-custom-option.admin__control-select').val();
            if(Object.values(config.category).length > 0){
            $('#canva-catalogue').attr("data-category", config.category['category'][k]);
            $('#canva-catalogue').attr("data-height", config.category['height'][k]);
            $('#canva-catalogue').attr("data-width", config.category['width'][k]);
            //if(!$('.swatch-attribute.size').length){
            // var hwo = jQuery('#canva-catalogue').attr('data-category');
            // if(hwo != undefined){
            //  hwo = hwo.split("_");
            //    var parsedValue = hwo[hwo.length - 1];
            //     hwo = parsedValue.split('x'); 
            //     console.log(hwo);
            //     jQuery('.product-custom-option').attr('data-crop',Math.floor(parseInt(hwo[0]))+':'+Math.floor(parseInt(hwo[1])));
            //     }
            // }
            }
        });

        setTimeout(function () {
            var productId = jQuery('#canva-catalogu').data("productid");
            var dispalyImage = jQuery('#canva-catalogu').data("canva-"+productId);
            if(!dispalyImage=="") {
                jQuery('.fotorama__active .fotorama__img').attr('src', dispalyImage);
            }
        }, 500);
    }
});

(async () => {
    var productData = {};
    var apiKey = jQuery('#canva-catalogue').data("api-key");
    var baseurl = jQuery('#canva-catalogue').data("url");
    var autoAuthToken = jQuery('#canva-catalogue').data("autoauthtoken");
    const api = await Canva.Partnership.initialize({
        apiKey: apiKey,
        autoAuthToken: autoAuthToken,
    });

    const onArtworkCreate = (opts) => {
        // the user has finished proofing their design
        productData['productId'] = jQuery('#canva-catalogue').attr('data-product-design-id');
        productData['previewImageSrc'] = opts.previewImageSrc;
        productData['artworkId'] = opts.artworkId;
        productData['designId'] = opts.designId;
        productData['pids'] = jQuery('#canva-catalogue').attr('data-pid');
        jQuery.ajax({
            showLoader: true,
            url: baseurl + 'onlinedesign/index/image',
            data: {productData: productData, edit: 1,producdetail:1},
            type: "POST",
            dataType: 'json',
        }).done(function (data) {
            console.log(data);
            //window.location.href = data.redirectUrl;
            var hrefurl= data.media_url;
            var last_part = hrefurl.substr(hrefurl.lastIndexOf('/') + 1);
            jQuery('.input-text.product-custom-option.Design').attr("value",last_part);
            jQuery('#product-canva-image').attr("data-product-canva-image",data.media_url);
            jQuery("#product-canva-image").val(data.media_url);
            jQuery('#upload_option').val("");
            jQuery('#canva-catalogue').attr("data-canva-design-id",data.canva_detail.designId);
            jQuery('span #canva-catalogue').html('Edit Design<span class="browse-temp">Canva\'s design tools let you enjoy designing without the fuss</span>');
            jQuery('.field.file').hide();
            if(jQuery('.product-custom-option.admin__control-select').length){
             //jQuery('.product-custom-option.admin__control-select').css('pointer-events','none');
            }
            
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
                jQuery("input[name='canva_custom_image']").attr("value",last_part);
                jQuery("input[name='canva_designid']").attr("value",opts.designId);
                jQuery("input[name='artworkid']").attr("value",opts.artworkId);
            }

        });
    };

    const createDesignButton = document.querySelector("#canva-catalogue");
    var ori = jQuery('.product-custom-option option:selected').text();

    jQuery('.product-custom-option').on('change', function() {
         var text = jQuery('.product-custom-option option:selected').text();
        
    });
    
    createDesignButton.onclick = () =>
    {
        /* Code for the form validation start */
        jQuery("#product-canva-image").removeClass("product-canva-option-required");
        jQuery("#upload_option").removeClass("product-custom-option-required");
        jQuery('#product_addtocart_form').validation();
        var formvalid = jQuery('#product_addtocart_form').validation('isValid');
        if (!formvalid) {
            return false;
        }

        /* Code for the form validation start */


        /* Hight and width for canva start from here */
        var hw = jQuery('.swatch-attribute.size').find('.swatch-option.selected').attr('data-option-label');
        var x = 0;
        if(typeof hw === "undefined"){
          hw = jQuery('#canva-catalogue').attr('data-category'); 
          
          hw = hw.split("_");
          parsedValue = hw[hw.length - 1]; 
          x =1;
        }else{
            parsedValue =  hw.substr(0,hw.indexOf(" "));
        }
        
        hw = parsedValue.split('x');
        console.log(hw);
        
        //var ori = jQuery('.product-custom-option option:selected').text();
        var ori = jQuery('.product-custom-option option:selected').prop('label');
        console.log(ori);
        if(x){
            
        var he = (ori=='Landscape')?parseFloat(hw[1]):parseFloat(hw[1]);
        var wi = (ori=='Landscape')?parseFloat(hw[0]):parseFloat(hw[0]);
        }else{
        var wi  = (ori=='Landscape')?parseInt(hw[1]):parseInt(hw[0]);
        var he = (ori=='Landscape')?parseInt(hw[0]):parseInt(hw[1]);
        }
        /* Hight and width for canva end here */

        /* Code for update cava image */
        if(jQuery('#product-updatecart-button').length){
            jQuery('#canva-catalogue').attr('data-canva-design-id', jQuery("input[name='canva_designid']").val());    
        }
        

        var partnerProductn = jQuery('#canva-catalogue').attr('data-category');
          
         partnerProductn = (partnerProductn!=undefined && partnerProductn!=="")?partnerProductn:jQuery('#canva-catalogue').attr('data-design-type');
         
         if(partnerProductn == "Sticker" ){
                partnerProductn = partnerProductn + 's';
          }

          //Add s in partner product id for flayer 
         if(partnerProductn == "Flyer" ){
                partnerProductn = partnerProductn + 's';
          } 
          
        if(jQuery('#canva-catalogue').attr('data-design-type') =="Tshirt"){
           partnerProductn = "T-shirts_14x18";
           wi = "14";
           he = "18"; 
         }
         var designId = jQuery('#canva-catalogue').attr('data-canva-design-id');
        console.log(partnerProductn); 
        if(designId!==''){
            api.editDesign({
            designId: designId,
            onArtworkCreate
            });
         }else{
            if(ori != undefined){
             partnerProductn = (x)?partnerProductn:partnerProductn+ '_'+wi+ 'x' +he;   
            api.createDesign({
            type: jQuery('#canva-catalogue').attr('data-design-type'),
            partnerProductId:partnerProductn ,
            height: he,
            width: wi,
            units: 'in',
            onArtworkCreate
            }); 
           } else{
            console.log(hw);
            var canva_size = jQuery('#canva-catalogue').attr('data-canva-size');
            if(jQuery('.swatch-attribute.shape').length){
               var shape = jQuery('.swatch-attribute.shape').find('.swatch-option.selected').attr('data-option-label');
               partnerProductn = partnerProductn +'_'+ shape;  
            }

            if(canva_size!=""){
              partnerProductn = partnerProductn + '_'+ canva_size;   
             }else{
               partnerProductn = partnerProductn + '_'+hw[0]+ 'x' +hw[1];
            }
            if(hw!=""){
              api.createDesign({
             type: jQuery('#canva-catalogue').attr('data-design-type'),
             partnerProductId:partnerProductn,
             onArtworkCreate 
             });  
         }else{
            api.createDesign({
             type: jQuery('#canva-catalogue').attr('data-design-type'),
             partnerProductId:partnerProductn,
             onArtworkCreate 
             });
            }
         }
         }
    };
})();