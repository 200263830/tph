define(['jquery'], function ($) {
    'use strict';
    var mixin = {
        _OnClick: function ($this, $widget) {

            var image =  $("#product-canva-image").attr("data-product-canva-image");
            if(image!=0)
            {
                setTimeout(function () {
                    jQuery('.fotorama__active .fotorama__img').attr('src', image);
                }, 150);
                setTimeout(function () {
                    jQuery('.fotorama__active .fotorama__img').attr('src', image);
                }, 200);
            }
            return this._super($this, $widget);
        }
    };

    return function (targetWidget) {
        $.widget('mage.SwatchRenderer', targetWidget, mixin);
        return $.mage.SwatchRenderer;
    };
});
