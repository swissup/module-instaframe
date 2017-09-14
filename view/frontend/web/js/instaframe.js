define([
    'jquery',
    'underscore',
    'jquery/ui',
    'Swissup_Instaframe/js/waterfall'
], function ($, _) {
    'use strict';

    $.widget('swissup.instaframe', {
        _create: function () {
            this._super();

            var el = this.element.get(0);

            require(['waterfall'], function(waterfall) {
                waterfall(el);

                var lazyWaterfall = _.debounce(function () {
                    waterfall(el);
                }, 100);

                window.addEventListener('resize', lazyWaterfall);
                window.addEventListener('orientationchange', lazyWaterfall);
            });

            /* FlipOnClick */
            $(function() {                       //run when the DOM is ready
                $(".instaframe-item").click(function() {  //use a class, since your ID gets mangled
                    $(this).toggleClass("clicked");      //add the class to the clicked element
                });

                $(".instaframe-likes").click(function(event){
                    event.stopPropagation();
                });

                $(".instaframe-comments").click(function(event){
                    event.stopPropagation();
                });
            });
        }
    });

    return $.swissup.instaframe;
});
