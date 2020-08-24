define(["jquery", "mage/translate", "jquery/ui"], function ($) {
    "use strict";

    var config = window["checkout"]["add_to_cart_notification"];

    var mixin = {
        /**
         * @param {jQuery} form
         */
        ajaxSubmit: function (form) {
            var productId = form.find('input[name="product"]').val();
            var self = this;

            this._super(form);

            self.element.find(".message.error").remove();

            $.when(this.productPromise(productId), this.cartPromise()).then(
                function (data) {
                    var product = data[0];
                    var title = $.mage.__("Added To Bag");
                    var message = $.mage.__('You added %1 to your <a href="%2">shopping cart</a>.')
                            .replace("%1", product["name"])
                            .replace("%2", product["cart_url"]);

                    var modalCloseBtn =
                        '<button class="action-close"><span>' + $.mage.__("Close") + "</span></button>";

                    // TODO: get width and height from product image xml config
                    var modalImage =
                        '<img class="notification-image" src="' +
                        product["image"] +
                        '" alt="' +
                        product["name"] +
                        '" width="80" height="80">';

                    $("body").append(
                        '<div id="add_to_cart_notification" role="dialog" class="modal-custom modal-added-to-cart">' +
                        '<div class="modal-header">' +
                        modalCloseBtn +
                        "</div>" +
                        '<div class="modal-content"><div class="product-image">' +
                        modalImage +
                        '</div><div class="product-content">' +
                        '<div class="block-title">' +
                        '<strong id="add_to_cart_notification-heading" role="heading" aria-level="2">' +
                        title +
                        "</strong></div>" +
                        '<div class="notification-message">' +
                        message +
                        "</div>" +
                        "</div></div></div>"
                    );

                    // Clear all add-to-cart messages,
                    // except our own notification.
                    $(".message.info.message-add-to-cart").remove();

                    $("#add_to_cart_notification")
                        .find(".action-close")
                        .click(function () {
                            self.removeNotification();
                        });

                    setTimeout(function () {
                        self.removeNotification();
                    }, config["notificationLifetime"]);
                },
                function (messages) {
                    if (self.element.find("div.mage-error").filter(":visible").length > 0) {
                        return;
                    }

                    var button = self.element.find("button.tocart");
                    messages.forEach(function (message) {
                        button.after(
                            '<div class="message info message-add-to-cart"><div>' +
                            message.text +
                            "</div></div>"
                        );
                    });
                }
            );
        },

        removeNotification: function () {
            $("#add_to_cart_notification").fadeOut(150, function () {
                $(this).remove();
            });
        },

        productPromise: function (productId) {
            return $.get({
                url: window["BASE_URL"] + "/cart_notification/data/product/product_id/" + productId,
                cache: true
            });
        },

        cartPromise: function () {
            var dfd = $.Deferred();

            $(document).on("ajaxSuccess", function (event, request, settings) {
                if (settings.url.indexOf("/checkout/cart/add") === -1) {
                    return;
                }

                if (request.responseJSON.error_messages) {
                    dfd.reject(request.responseJSON.error_messages);
                    $(document).off("ajaxSuccess");
                    return;
                }

                dfd.resolve();
                $(document).off("ajaxSuccess");
            });

            return dfd.promise();
        }
    };

    return function (target) {
        if (!config["enabled"]) {
            return target;
        }

        $.widget("mooore.catalogAddToCart", target, mixin);

        return $.mooore.catalogAddToCart;
    };
});
