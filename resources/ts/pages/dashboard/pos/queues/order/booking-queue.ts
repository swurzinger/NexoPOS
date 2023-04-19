import { Queue } from "~/contracts/queue";
import { Popup } from "~/libraries/popup";

import { default as nsPaymentPopup } from '~/popups/ns-pos-payment-popup.vue';
import nsPosLoadingPopupVue from "~/popups/ns-pos-loading-popup.vue";
import {nsSnackBar} from "~/bootstrap";

export class BookingQueue implements Queue {
    constructor( private order ) {}

    async run() {
        return await this.submitOrder({payment_status: 'unpaid'});
    }


    private async submitOrder(data = {}) {
        const popup     =   Popup.show( nsPosLoadingPopupVue );

        try {
            const order     =   { ...this.order, ...data };

            const result = await POS.submitOrder( order );

            popup.close();
            nsSnackBar.success( result.message ).subscribe();

            POS.printOrderReceipt( result.data.order, 'silent' );

        } catch( exception ) {
            popup.close();
            nsSnackBar.error( exception.message ).subscribe();
        }
    }
}

(<any>window).BookingQueue    =   BookingQueue;
