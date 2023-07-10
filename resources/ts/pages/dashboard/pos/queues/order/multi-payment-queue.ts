import { Queue } from "~/contracts/queue";
import { Popup } from "~/libraries/popup";

import nsPosMultiPaymentPopup from "~/popups/ns-pos-multi-payment-popup.vue";
import {nsSnackBar} from "~/bootstrap";
import {__} from "~/libraries/lang";
import {TypeQueue} from "~/pages/dashboard/pos/queues/order/type-queue";

export class MultiPaymentQueue implements Queue {
    constructor( private order ) {}

    async run() {

        // we allow to show the payment dialog even if there's nothing to pay as we can do simple customer account
        // transactions there as well

        // if (this.order.customer.owed_amount <= 0 && (this.order.total - this.order.tendered) <= 0) {
        //     nsSnackBar.info( __( 'Keine unbezahlten Bestellungen' ) ).subscribe();
        //     throw new Error("no unpaid orders");
        // }

        if ( this.order.products.length > 0 ) {
            await (new TypeQueue(this.order)).run();
        }

        await this.showMultiPaymentPopup();
    }

    private showMultiPaymentPopup() {
        return new Promise( ( resolve, reject ) => {
            Popup.show( nsPosMultiPaymentPopup, { resolve, reject, order : this.order })
        })
    }
}

(<any>window).MultiPaymentQueue    =   MultiPaymentQueue;
