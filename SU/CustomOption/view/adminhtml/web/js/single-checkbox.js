define([
    'Magento_Ui/js/form/element/single-checkbox',
    'mage/translate',
    'uiRegistry'
], function (AbstractField, $t, registry) {
    'use strict';

    return AbstractField.extend({
        defaults: {
            modules: {
                // su_custom: '${ $.parentName }.options.0.container_option.container_common.depend',
                su_custom: '${ $.parentName }.depend',

            }

        },

        updateStatus: function () {
            // console.log(this.su_custom());
            // var index = 0;
            // while (index < this.su_custom().maxPosition){
            let field = this.su_custom();
            //     if (field !== undefined){
            //         if (parseInt(this.value()) === 1) {
            //             field.show();
            //         } else {
            //             field.hide();
            //         }
            //     }
            //     index++;
            // }
            if (field !== undefined){
                if (parseInt(this.value()) === 1) {
                    field.show();
                } else {
                    field.hide();
                }
            }
        },

        onCheckedChanged: function () {
            this._super();
            this.updateStatus();
        }
    });
});