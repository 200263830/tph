(async() => {
 var productData = {};
var apiKey = jQuery('#edit-design').data("api-key");
var autoAuthToken = jQuery('#edit-design').data("auth-token");
const editDesignButton = document.querySelector(".tbh-edit-design");
const api = await
Canva.Partnership.initialize({
    apiKey: apiKey,
    autoAuthToken: autoAuthToken
});
var baseurl = jQuery('#edit-design').data("url");

const onProductSelect = (opts) => {
        api.createDesign({
            ...opts,
        onArtworkCreate,
});
};

const onArtworkCreate = (opts) =>
{
    // the user has finished proofing their design
    productData['productId'] = jQuery('#edit-design').data("product-id");
    productData['productSizeOptionId'] = jQuery('#edit-design').data("size-option");
    productData['previewImageSrc'] = opts.previewImageSrc;
    productData['artworkId'] = opts.artworkId;
    productData['designId'] = opts.designId;
    jQuery.ajax({
        showLoader: true,
        url: baseurl + 'onlinedesign/index/image',
        data: {productData: productData,edit:0,producdetail:1},
        type: "POST",
        dataType: 'json',
    }).done(function (data) {
        console.log(data);
        jQuery('.fotorama__active .fotorama__img').attr('src', data.media_url);
        jQuery('#product-canva-image').attr("data-product-canva-image",data.media_url);
        jQuery("#product-canva-image").val(data.media_url);
        
    });

};

jQuery(".tbh-edit-design").on('click', function(event){
    var design = jQuery(this).attr('data-design-id');
    productData['pid'] = jQuery(this).attr('data-pid');
    api.editDesign({
        designId: design,
        onProductSelect,
        onArtworkCreate
    });
});

})();

define([
    'Magento_Customer/js/customer-data'
    ], function (customerData) {
    return function () {
        var sections = ['cart'];
        customerData.invalidate(sections);
        customerData.reload(sections, true);
    }
});