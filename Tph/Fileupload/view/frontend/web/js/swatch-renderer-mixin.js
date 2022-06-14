define(['jquery'], function ($) {
    'use strict';

    var modalWidgetMixin = {
        options: {
            uploadImageSelector: "#upload_option"
        },

        /**
         * Added confirming for modal closing
         *
         * @returns {Element}
         */
        updateBaseImage: function (images, context, isInProductView) {
            var uploadedImage = $(this.options.uploadImageSelector).val();
            if(uploadedImage != "" && typeof uploadedImage!= 'undefined') {
                this.options.gallerySwitchStrategy = 'replace';
                //console.log(uploadedImage);
                images.push({
                    //img: jQuery('#upload_option').val(),
                    thumb: uploadedImage,
                    //full: jQuery('#upload_option').val(),
                    caption: 'caption',
                    position: 2
                });
            }
            this._super(images, context, isInProductView);

        }
    };

    return function (targetWidget) {
        // Example how to extend a widget by mixin object
        $.widget('mage.SwatchRenderer', targetWidget, modalWidgetMixin); // the widget alias should be like for the target widget

        return $.mage.SwatchRenderer; //  the widget by parent alias should be returned
    };
});
