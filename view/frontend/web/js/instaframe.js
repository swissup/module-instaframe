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
            // return;
            require(['waterfall'], function(waterfall) {
                waterfall(el);
            });
        }
    });
});
