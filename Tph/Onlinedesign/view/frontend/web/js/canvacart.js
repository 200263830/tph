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
        data: {productData: productData,edit:1,producdetail:1},
        type: "POST",
        dataType: 'json',
    }).done(function (data) {
        console.log(data);
        jQuery('.product-image-container-' + data.product_id).find('.product-image-photo').attr('src', data.media_url);
        //window.location.href = data.redirectUrl;
        window.location.reload();
    });

};

jQuery(".tbh-edit-design").on('click', function(event){
    var design = jQuery(this).attr('data-design-id');
    productData['pid'] = jQuery(this).attr('data-pid');
    productData['did'] = jQuery(this).attr('data-design-id');
    api.editDesign({
        designId: design,
        type:"Businesscards",
        partnerProductId: "Business_Cards", 
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