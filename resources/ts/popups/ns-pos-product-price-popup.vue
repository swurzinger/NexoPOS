<template>
    <div class="ns-box shadow-lg w-95vw md:w-3/5-screen lg:w-2/5-screen">
        <div class="popup-heading ns-box-header">
            <h3>{{ __('Product Price') }}</h3>
            <div>
                <ns-close-button @click="popupResolver( false )"></ns-close-button>
            </div>
        </div>
        <div class="flex flex-col ns-box-body">
            <div class="h-16 flex items-center justify-center elevation-surface info font-bold">
                <h2 class="text-2xl">{{ nsCurrency(price) }}</h2>
            </div>
            <ns-numpad :floating="true" @changed="updateProductPrice( $event )" @next="resolveProductPrice( $event )" :value="rawPrice"></ns-numpad>
        </div>
    </div>
</template>
<script>
import {ref} from '@vue/reactivity';
import {nsNumpad, nsNumpadPlus} from '~/components/components';
import {nsCurrency} from '~/filters/currency';
import popupResolver from "~/libraries/popup-resolver";
import popupCloser from "~/libraries/popup-closer";
import NsCloseButton from "~/components/ns-close-button.vue";

export default {
    name: 'ns-pos-product-price-product',
    props: ['popup'],
    components: {
        NsCloseButton,
        nsNumpad,
        nsNumpadPlus
    },
    computed: {
        // ...
    },
    data() {
        return {
            product: {},
            optionsSubscription: null,
            options: {},
            price: 0,
            rawPrice: '',
        }
    },
    mounted() {
        this.popupCloser();

        this.product = this.popup.params.product;
        this.updateProductPrice(null);

        this.optionsSubscription = POS.options.subscribe(options => {
            this.options = ref(options);
        });
    },
    beforeUnmount() {
        this.optionsSubscription.unsubscribe();
    },
    methods: {
        popupResolver,
        popupCloser,
        nsCurrency,
        __,

        updateProductPrice(price) {
            this.rawPrice = price;
            this.price = (price === null || price === undefined) ? this.product.unit_price : (parseFloat(price) || 0);
        },

        resolveProductPrice(price) {
            this.popupResolver(this.price);
        }
    }
}
</script>
