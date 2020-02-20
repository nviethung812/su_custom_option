define([
    'Magento_Ui/js/form/element/single-checkbox',
    'uiRegistry',
    'jquery',
    'domReady!'
], function (AbstractField, registry, $) {
    'use strict';

    $(document).ajaxComplete(function () {
        var options = registry.get("product_form.product_form.custom_options.options");
        if (options !== undefined){
            var length = options.maxPosition;
            var index = 0;
            while (index < length){
                let check = registry.get("product_form.product_form.custom_options.options." + index + ".container_option.container_common.is_depend_option_enabled");
                if (check !== undefined){
                    let field = registry.get("product_form.product_form.custom_options.options." + index + ".container_option.container_common.depend");
                    if (parseInt(check.initialValue) === 1) {
                        field.show();
                    } else {
                        field.hide();
                    }
                }
                index++;
            }
        }
    });

    return AbstractField.extend({
        defaults: {
            modules: {
                // su_custom: '${ $.parentName }.options.0.container_option.container_common.depend',
                su_custom: '${ $.parentName }.depend',
            }
        },

        updateStatus: function () {
            let field = this.su_custom();
            // console.log(field);
            if (field !== undefined){
                if (parseInt(this.value()) === 1) {
                    field.show();
                } else {
                    field.value('');
                    field.hide();
                }
            }
        },

        onCheckedChanged: function () {
            this._super();
            this.updateStatus();
        },
    });
});