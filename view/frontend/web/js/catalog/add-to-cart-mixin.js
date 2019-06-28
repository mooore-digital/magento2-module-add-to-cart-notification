define([
    'jquery',
    'mage/translate',
    'jquery/ui'
], function ($) {
    'use strict';

    var NOTIFICATION_LIFETIME = 1500;

    var config = window['checkout']['add_to_cart_notification'];

    var mixin = {
        /**
         * @param {jQuery} form
         */
        ajaxSubmit: function (form) {
            var productId = form.find('input[name="product"]').val();

            this._super(form);

            $.when(
                this.productPromise(productId),
                this.cartPromise()
            ).then(function (data) {
                var product = data[0];
                var title = $.mage.__('Added To Bag');
                var message = $.mage.__('You added %1 to your <a href="%2">shopping cart</a>.')
                    .replace('%1', product['name'])
                    .replace('%2', product['cart_url']);

                $('body').append(
                    '<div id="add_to_cart_notification" class="add-to-cart">' +
                    '<div class="btn-close"></div>' +
                    '<img class="notification-image" src="' + product['image'] + '" alt="' + product['name'] + '" title="' + product['name'] + '">' +
                    '<div class="notification-content">' +
                    '<p class="notification-title">' + title + '</p>' +
                    '<p class="notification-message">' + message + '</p>' +
                    '</div>' +
                    '</div>'
                );

                $('#add_to_cart_notification').click(function () {
                    $(this).remove();
                })

                setTimeout(function () {
                    // $('#add_to_cart_notification').remove();
                }, config['notificationLifetime']);
            });
        },

        productPromise: function (productId) {
            return $.get({
                url: window['BASE_URL'] + '/cart_notification/data/product/product_id/' + productId,
                cache: true
            });
        },

        cartPromise: function () {
            var dfd = $.Deferred();

            $(document).on('ajaxSuccess', function (event, request, settings) {
                if (!settings.url.indexOf('/checkout/cart/add')) {
                    return;
                }

                dfd.resolve();

                $(document).off('ajaxSuccess');
            });

            return dfd.promise();
        }
    };

    return function (target) {
        if (!config['enabled']) {
            return target;
        }

        $.widget('marissen.catalogAddToCart', target, mixin);

        return $.marissen.catalogAddToCart;
    }
});
