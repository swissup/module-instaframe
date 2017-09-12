define([
    'jquery',
    'uiComponent',
    'Swissup_Instaframe/js/waterfall'
], function ($, Component) {
    'use strict';

    return Component.extend({
        options: {
            //
        },

        initialize: function (options, el) {
            this._super();
            require(['waterfall'], function(waterfall) {
                waterfall(el);
                window.addEventListener('resize', function () {
                    waterfall(el);
                });
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
});
