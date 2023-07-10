<template>
    <div class="h-full w-full py-2">
        <div class="px-2 pb-2">
            <div class="grid grid-cols-2 gap-2">
                <div id="details" class="h-16 flex justify-between items-center elevation-surface border info text-xl md:text-3xl p-2">
                    <span>{{ __( 'Total' ) }} : </span>
                    <span>{{ nsCurrency( sums.total ) }}</span>
                </div>
                <!--
                <div id="discount" @click="toggleDiscount()" class="cursor-pointer h-16 flex justify-between items-center elevation-surface error border text-xl md:text-3xl p-2">
                    <span>{{ __( 'Discount' ) }} : </span>
                    <span>{{ nsCurrency( order.discount ) }}</span>
                </div>-->
                <div id="paid" class="h-16 flex justify-between items-center elevation-surface success border text-xl md:text-3xl p-2">
                    <span>{{ __( 'Paid' ) }} : </span>
                    <span>{{ nsCurrency( sums.paid ) }}</span>
                </div>
                <div id="change" class="h-16 flex justify-between items-center elevation-surface warning border text-xl md:text-3xl p-2">
                    <span>{{ __( 'To Pay' ) }} : </span>
                    <span>{{ nsCurrency( sums.remaining ) }}</span>
                </div>
                <div id="paid" class="h-16 flex justify-between items-center elevation-surface success border text-xl md:text-3xl p-2">
                    <span>{{ __( 'Cash' ) }} : </span>
                    <span>{{ nsCurrency( sums.paidCash ) }}</span>
                </div>

                <div id="change" class="col-span-2 h-16 flex justify-between items-center elevation-surface border success text-xl md:text-3xl p-2">
                    <span>{{ __( 'Current Balance' ) }} ({{ __( 'Customer Account' ) }}) : </span>
                    <span>{{ nsCurrency( sums.accountBalance ) }}</span>
                </div>
                <!--
                <div id="change" class="col-span-2 h-16 flex justify-between items-center elevation-surface border text-primary text-xl md:text-3xl p-2">
                    <span>{{ __( 'Screen' ) }} : </span>
                    <span>{{ nsCurrency( screenValue ) }}</span>
                </div>
                -->
            </div>
        </div>
        <div class="px-2 pb-2">
            <div class="-mx-2 flex flex-wrap">
                <div class="pl-2 pr-1 flex-auto" v-if="sums.total > 0">
                    <div
                        @click="autoAssign( sums.accountBalance, 'account-payment')"
                        class="elevation-surface border hoverable text-2xl text-primary h-16 flex items-center justify-center cursor-pointer">
                        <span>Automatisch zuweisen</span>
                    </div>
                    <div
                        v-if="sums.remaining > 0"
                        @click="autoAssign( sums.remaining, 'cash-payment' )"
                        class="mt-2 elevation-surface border hoverable text-2xl text-primary h-16 flex items-center justify-center cursor-pointer">
                        <span>Barzahlung {{ nsCurrency( sums.remaining ) }}</span>
                    </div>
<!--                    <ns-numpad :floating="true" @changed="handleChange( $event )" @next="proceedAddingPayment( $event )">-->
<!--                        <template v-slot:numpad-footer>-->
<!--                            <div-->
<!--                            @click="makeFullPayment()"-->
<!--                            class="hover:bg-success-tertiary col-span-3 bg-success-secondary text-2xl text-white border border-success-secondary h-16 flex items-center justify-center cursor-pointer">-->
<!--                            {{ __( 'Full Payment' ) }}</div>-->
<!--                        </template>-->
<!--                    </ns-numpad>-->
                </div>
                <div class="pl-2 pr-1 flex-auto items-center justify-center flex" v-else>
                  <span class="text-xl text-primary">
                    Nichts zu bezahlen 😎
                  </span>
                </div>
                <div class="w-1/2 md:w-72 pr-2 pl-1">
                    <div class="grid grid-flow-row grid-rows-1 gap-2">
                        <div class="text-xl text-primary items-center justify-center flex">
                            Aufladen:
                        </div>
                        <div :key="index" v-for="( value, index ) of [10, 20, 50, 100]"
                             @click="accountTransaction(value)"
                             class="elevation-surface border hoverable text-2xl text-primary h-16 flex items-center justify-center cursor-pointer">
                            <span>{{ nsCurrency( value ) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script lang="ts">
import nsNumpad from "~/components/ns-numpad.vue";
import { nsSnackBar } from '~/bootstrap';
import nsPosConfirmPopupVue from '~/popups/ns-pos-confirm-popup.vue';
import { __ } from '~/libraries/lang';
import {nsCurrency, nsMoney} from '~/filters/currency';
import { Popup } from "~/libraries/popup";
import nsCustomersTransactionPopupVue from "~/popups/ns-customers-transaction-popup.vue";
import {ref} from "@vue/reactivity";

declare const POS;

export default {
    name: "ns-multi-payment",
    components: {
        nsNumpad
    },
    props: [ 'orders' ],
    data() {
        return {
            subscription: null,
            screenValue: 0,
            order: null,
        }
    },
    computed: {
        sums() {
            let sums = this.orders.reduce(function (result, order) {
                let paidCash = order.payments.filter( p => p.identifier === 'cash-payment')
                    .reduce( (t, p) => t.add(p.value), nsMoney(0) );
                let paidAccount = order.payments.filter( p => p.identifier === 'account-payment')
                    .reduce( (t, p) => t.add(p.value), nsMoney(0) );
                return {
                    total: result.total.add(Math.max(0, order.total - order.tendered)),
                    paid: result.paid.add(paidCash).add(paidAccount),
                    paidCash: result.paidCash.add(paidCash),
                    paidAccount: result.paidAccount.add(paidAccount),
                };
            }, { total: nsMoney(0), paid: nsMoney(0), paidCash: nsMoney(0), paidAccount: nsMoney(0) });
            const accountBalance = this.order?.customer ? nsMoney(this.order.customer.account_amount).subtract(sums.paidAccount) : 0;
            return {
                total: Number(sums.total),
                paid: Number(sums.paid),
                paidCash: Number(sums.paidCash),
                paidAccount: Number(sums.paidAccount),
                remaining: Math.max(0, sums.total.subtract(sums.paid).value),
                accountBalance: Number(accountBalance)
            };
        }
    },
    methods: {
        __,
        nsCurrency,
        // handleChange( event ) {
        //     this.screenValue    =   event;
        // },
        // proceedAddingPayment( event ) {
        //     const value    =   parseFloat( event );
        //     const payments  =   this.order.payments;
        //
        //     if ( value <= 0 ) {
        //         return nsSnackBar.error( __( 'Please provide a valid payment amount.' ) )
        //             .subscribe();
        //     }
        //
        //     if ( payments.filter( p => p.identifier === 'account-payment' ).length > 0 ) {
        //         return nsSnackBar.error( __( 'The customer account can only be used once per order. Consider deleting the previously used payment.' ) )
        //             .subscribe();
        //     }
        //
        //     if ( value > this.order.customer.account_amount ) {
        //         return nsSnackBar.error( __( 'Not enough funds to add {amount} as a payment. Available balance {balance}.' )
        //             .replace( '{amount}', nsCurrency( value ) )
        //             .replace( '{balance}', nsCurrency( this.order.customer.account_amount ) )
        //         ).subscribe();
        //     }
        //
        //     POS.addPayment({
        //         value,
        //         identifier: 'account-payment',
        //         selected: false,
        //         label: 'Account payment',
        //         readonly: false,
        //     });
        //
        //     this.order.customer.account_amount  -=  value;
        //     POS.selectCustomer( this.order.customer );
        //
        //     this.$emit( 'submit' );
        // },
        // proceedFullPayment() {
        //     this.proceedAddingPayment( this.order.total );
        // },
        autoAssign( amountToAssign: number, paymentMethod: string ) {
            let remainingAmount = nsMoney(amountToAssign);
            for (const o of this.orders) {
                if (remainingAmount.intValue <= 0) break;
                const totalPayments = o.payments.reduce( (t, p) => t.add(p.value), nsMoney(0) );
                const toBePaid = Math.max(0, nsMoney(o.total).subtract(o.tendered).subtract(totalPayments).value);
                if (toBePaid > 0) {
                    const payment = Math.min(remainingAmount.value, toBePaid);
                    o.payments.push({
                        'label': paymentMethod === 'cash-payment' ? 'Barzahlung' : 'Kundenkonto',
                        'identifier': paymentMethod,
                        'value': payment,
                    });
                    remainingAmount = remainingAmount.subtract(payment);
                }
            }
        },
        accountTransaction( value ) {
            const customer = this.order.customer;
            const promise = new Promise((resolve, reject) => {
                Popup.show(nsCustomersTransactionPopupVue, {
                    customer,
                    resolve,
                    reject,
                    defaults: {"operation": "add", "amount": value, "description": "Aufladung"}
                });
            });

            promise.then( result => {
                POS.loadCustomer( customer.id )
                    .subscribe( _customer => {
                        POS.selectCustomer( _customer );
                    });
            })
        },

        // makeFullPayment() {
        //     Popup.show( nsPosConfirmPopupVue, {
        //         title: __( 'Confirm Full Payment' ),
        //         message: __( 'You\'re about to use {amount} from the customer account to make a payment. Would you like to proceed ?' ).replace( '{amount}', nsCurrency( this.order.total ) ),
        //         onAction: ( action ) => {
        //             if ( action ) {
        //                 this.proceedFullPayment();
        //             }
        //         }
        //     });
        // },
    },
    mounted() {
        this.subscription   =   POS.order.subscribe( order => this.order = ref(order) );
    },
    unmounted() {
        this.subscription.unsubscribe();
    }
}
</script>
