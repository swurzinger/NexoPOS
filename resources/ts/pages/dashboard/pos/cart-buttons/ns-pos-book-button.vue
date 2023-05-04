<template>
    <div @click="bookOrder()" id="book-button" class="flex-shrink-0 w-1/2 flex items-center font-bold cursor-pointer justify-center bg-blue-500 text-white hover:bg-blue-600 border-r border-blue-600 flex-auto">
        <i class="mr-2 text-2xl lg:text-xl las la-shopping-basket"></i>
        <span class="text-lg hidden md:inline lg:text-2xl">{{ __( 'Book' ) }}</span>
    </div>
</template>
<script lang="ts">
import {ProductsQueue} from "~/pages/dashboard/pos/queues/order/products-queue";
import {CustomerQueue} from "~/pages/dashboard/pos/queues/order/customer-queue";
import {TypeQueue} from "~/pages/dashboard/pos/queues/order/type-queue";
import {BookingQueue} from "~/pages/dashboard/pos/queues/order/booking-queue";
import {nsHooks} from "~/bootstrap";
import {__} from "~/libraries/lang";

declare const nsHotPress;
declare const nsShortcuts;

export default {
    props: [ 'order' ],
    methods: {
        __,
        async bookOrder() {
            const queues    =   nsHooks.applyFilters( 'ns-booking-queue', [
                ProductsQueue,
                CustomerQueue,
                TypeQueue,
                BookingQueue,
            ]);

            for( let index in queues ) {
                try {
                    const promise   =   new queues[ index ]( this.order );
                    const response  =   await promise.run();
                } catch( exception ) {
                    /**
                     * in case there is something broken
                     * on the promise, we just stop the queue.
                     */
                    return false;
                }
            }
        },
    },
    mounted() {
        /**
         * let's register hotkeys
         */
         for( let shortcut in nsShortcuts ) {
            if ([
                    'ns_pos_keyboard_hold_order',
                ].includes( shortcut ) ) {
                nsHotPress
                    .create( 'ns_pos_keyboard_hold_order' )
                    .whenNotVisible([ '.is-popup' ])
                    .whenPressed( nsShortcuts[ shortcut ], ( event ) => {
                        event.preventDefault();
                        this.bookOrder();
                });
            }
        }
    },
    unmounted() {
        nsHotPress.destroy( 'ns_pos_keyboard_hold_order' );
    }
}
</script>
