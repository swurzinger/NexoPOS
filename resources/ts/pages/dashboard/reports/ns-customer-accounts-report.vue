<template>
    <div id="report-section">
        <div class="flex -mx-2">
<!--            <div class="px-2">-->
<!--                <ns-date-time-picker :field="startDateField"></ns-date-time-picker>-->
<!--            </div>-->
<!--            <div class="px-2">-->
<!--                <ns-date-time-picker :field="endDateField"></ns-date-time-picker>-->
<!--            </div>-->
            <div class="px-2">
                <div class="ns-button">
                    <button @click="printSaleReport()" class="rounded flex justify-between text-primary shadow py-1 items-center  px-2">
                        <i class="las la-print text-xl"></i>
                        <span class="pl-2">{{ __( 'Print' ) }}</span>
                    </button>
                </div>
            </div>
        </div>
        <div id="report" class="anim-duration-500 fade-in-entrance">
            <div class="flex w-full">
                <div class="my-4 flex justify-between w-full">
                    <div class="text-primary">
                        <ul>
                            <li class="pb-1">{{ __( 'Date : {date}' ).replace( '{date}', ns.date.current ) }}</li>
<!--                            <li class="pb-1">{{ __( 'Document' ) }} : {{ __('Customer Accounts Overview') }}</li>-->
<!--                            <li class="pb-1">{{ __( 'By : {user}' ).replace( '{user}', ns.user.username ) }}</li>-->
                        </ul>
                    </div>
                    <div>
                        <img class="w-24" :src="storeLogo" :alt="storeName">
                    </div>
                </div>
            </div>
            <div class="shadow rounded">
                <div class="ns-box">
                    <div class="text-center ns-box-header p-2">
                        <h3 class="font-bold">{{ __( 'Customer Accounts' ) }}</h3>
                    </div>
                    <div class="border-b ns-box-body">
                        <table class="table ns-table w-full">
                            <thead>
                                <tr>
                                    <th class="p-2 border text-left">{{ __( 'Name' ) }}</th>
                                    <th class="p-2 border text-right">{{ __( 'Balance' ) }}</th>
                                </tr>
                            </thead>
                            <tbody class="text-primary">
                                <tr v-for="customer in report.customers" :key="customer.id">
                                    <td class="p-2 border text-left">{{ customer.name }}</td>
                                    <td class="p-2 border text-right" :class="balanceColor(customer.balance)">
                                        {{ nsCurrency( customer.balance ) }}
                                    </td>
                                </tr>
                                <tr class="">
                                    <td class="font-semibold p-2 border text-left">{{ __( 'Total' ) }}</td>
                                    <td class="font-semibold p-2 border text-right" :class="balanceColor(report.total_balance)">
                                        {{ nsCurrency( report.total_balance ) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
<!--            <br><br>-->
<!--            <div class="shadow rounded overflow-hidden" v-if="report.orders.length > 0">-->
<!--                <div class="ns-box">-->
<!--                    <div class="text-center ns-box-header p-2">-->
<!--                        <h3 class="font-bold">{{ __( 'Orders' ) }}</h3>-->
<!--                    </div>-->
<!--                    <div class="border-b ns-box-body">-->
<!--                        <table class="table ns-table w-full">-->
<!--                            <thead>-->
<!--                                <tr>-->
<!--                                    <th class="p-2 border text-left">{{ __( 'Order' ) }}</th>-->
<!--                                    <th class="p-2 border text-right">{{ __( 'Total' ) }}</th>-->
<!--                                </tr>-->
<!--                            </thead>-->
<!--                            <tbody class="text-primary">-->
<!--                                <tr class="" v-for="order of report.orders" :key="order.id">-->
<!--                                    <td width="200" class="font-semibold p-2 border text-left">{{ order.code }}</td>-->
<!--                                    <td class="p-2 border text-right">{{ nsCurrency( order.total ) }}</td>-->
<!--                                </tr>-->
<!--                            </tbody>-->
<!--                        </table>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
        </div>
    </div>
</template>
<script lang="ts">
import { nsCurrency } from '~/filters/currency';
import { __ } from '~/libraries/lang';
import NsDateTimePicker from "~/components/ns-date-time-picker.vue";
import NsSearch from "~/components/ns-search.vue";

declare const nsSnackBar;

export default {
    name: 'ns-customer-accounts-report',
    components: {NsSearch, NsDateTimePicker},
    props: [ 'store-logo', 'store-name', 'search-url' ],
    data() {
        return {
            ns: window.ns,
            report: {
                total_owed: 0,
                total_account: 0,
                total_purchases: 0,
                total_balance: 0,
                customers: [],
            }
        }
    },
    mounted() {
        nsHttpClient.get( `/api/mvl/reports/customer-accounts`, {}).subscribe({
            next: report => {
                this.report     =   report;
            },
            error: error => {
                nsSnackBar.error( error.message || __( 'An unexpected error occured' ) ).subscribe();
            }
        })
    },
    computed: {
        // ...
    },
    methods: {
        __,
        nsCurrency,
        printSaleReport() {
            this.$htmlToPaper( 'report' );
        },
        balanceColor(value: number) {
            return ({
                'bg-success-primary': value > 0,
                'bg-error-primary': value < 0,
            })
        }
    }
}
</script>
