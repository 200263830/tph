define([
    'jquery',
    'jquery/ui',
    'jquery/validate',
    'mage/translate',
    'domReady!'
], function ($) {
    'use strict';
    var uploadvalid = $('#upload_option').attr('data-upload-valid');
    uploadvalid = parseInt(uploadvalid);
    if(uploadvalid!==0){
        console.log("come in validation");
        $("#upload_option").addClass("product-custom-option-required");
        $.validator.addMethod(
            'product-custom-option-required',
            function (value) {
                if(value) return true;
                else
                    return false;
                },
            $.mage.__('Please select/upload Your file!')
        );
    }

    jQuery(document).on('click', '.swatch-attribute.size .swatch-option',function(){

        var hw = jQuery(".swatch-attribute.size .swatch-option.selected").attr('aria-label');


        if(hw!=undefined){
        var parsedValue =  hw.substr(0,hw.indexOf(" "));
         hw = parsedValue.split('x');
        var ratio = getRatio(hw[0],hw[1]);
        ratio = ratio.split('_');
       $('.product-custom-option').attr('data-crop',ratio[0]+':'+ratio[1]);

       var orientation = jQuery('.product-custom-option option:selected').prop('label');

         var max = Math.max(hw[0],hw[1]);
         var min = Math.min(hw[0],hw[1]);
         console.log(orientation);
            if(orientation == 'Landscape')
            {
                var ratioPotrait = getRatio(max,min);
                console.log(ratioPotrait);
                ratioPotrait = ratioPotrait.split('_');
                $('.product-custom-option').attr('data-crop',ratioPotrait[0]+':'+ratioPotrait[1]);
            }
            else
            {
                var ratioLanscape = getRatio(min,max);
                console.log(ratioLanscape);
                ratioLanscape = ratioLanscape.split('_');
                $('.product-custom-option').attr('data-crop',ratioLanscape[0]+':'+ratioLanscape[1]);

            }

       }else{
        console.log('Not able to get size ');
       }
     });

    $('.product-custom-option').change(function(){
        if($( ".swatch-option" ).hasClass( "selected" ))
        {

            var hw = jQuery(".swatch-attribute.size .swatch-option.selected").attr('aria-label');
            console.log(hw);
            if(hw!=undefined) {
                var parsedValue =  hw.substr(0,hw.indexOf(" "));
                hw = parsedValue.split('x');
                var ratio = getRatio(hw[0], hw[1]);
                ratio = ratio.split('_');
                $('.product-custom-option').attr('data-crop', ratio[0] + ':' + ratio[1]);

                var orientation = jQuery('.product-custom-option option:selected').prop('label');

                var max = Math.max(hw[0], hw[1]);
                var min = Math.min(hw[0], hw[1]);
                console.log(orientation);
                if(orientation == 'Landscape')
                {
                    var ratioPotrait = getRatio(max,min);
                    console.log(ratioPotrait);
                    ratioPotrait = ratioPotrait.split('_');
                    $('.product-custom-option').attr('data-crop',ratioPotrait[0]+':'+ratioPotrait[1]);
                }
                else
                {
                    var ratioLanscape = getRatio(min,max);
                    console.log(ratioLanscape);
                    ratioLanscape = ratioLanscape.split('_');
                    $('.product-custom-option').attr('data-crop',ratioLanscape[0]+':'+ratioLanscape[1]);

                }
            }
        }
    });
});

function getRatio(num1, num2){
    for(let i = num2; i > 1; i--) {
        if((num1 % i) == 0 && (num2 % i) == 0) {
            num1 = num1 / i;
            num2 = num2 / i;
        }
    }
    return num1+'_'+num2;
}
