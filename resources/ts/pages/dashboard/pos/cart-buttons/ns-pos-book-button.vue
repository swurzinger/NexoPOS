<template>
    <div @click="bookOrder()" id="book-button" class="flex-shrink-0 w-1/4 flex items-center font-bold cursor-pointer justify-center bg-blue-500 text-white hover:bg-blue-600 border-r border-blue-600 flex-auto">
        <i class="mr-2 text-2xl lg:text-xl las la-shopping-basket"></i>
        <span class="text-lg hidden md:inline lg:text-2xl">{{ __( 'Book' ) }}</span>
    </div>
</template>
<script lang="ts">
declare const POS;
declare const nsShortcuts;
declare const nsHotPress;
declare const __;

export default {
    props: [ 'order' ],
    methods: {
        __,
        async bookOrder() {
            POS.runBookingQueue();
        },
    },
    mounted() {
        /**
         * let's register hotkeys
         */
         for( let shortcut in nsShortcuts ) {
            if ([
                    'ns_pos_keyboard_booking',
                ].includes( shortcut ) ) {
                nsHotPress
                    .create( 'ns_pos_keyboard_booking' )
                    .whenNotVisible([ '.is-popup' ])
                    .whenPressed( nsShortcuts[ shortcut ] !== null ? nsShortcuts[ shortcut ].join( '+' ) : null, ( event ) => {
                        event.preventDefault();
                        this.bookOrder();
                });
            }
        }
    },
    unmounted() {
        nsHotPress.destroy( 'ns_pos_keyboard_booking' );
    }
}
</script>
