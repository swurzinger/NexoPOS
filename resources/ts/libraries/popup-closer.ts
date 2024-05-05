declare const nsHotPress;
declare const nsState;

/**
 * Must be used on component
 * that has the popup object defined.
 */
export default function() {
    if ( this.popup !== undefined ) {
        /**
         * We'll listen to "esc" keypress
         * but proceed in certain conditions.
         */
        const identifier =  `popup-esc-${this.popup.hash}`;
        nsHotPress.create( identifier )
            .whenPressed( 'escape', ( event ) => {
                event.preventDefault();

                const currentPopup = document.querySelector( `#${this.popup.hash}` );

                /**
                 * If the popup is not focused then
                 * we don't want to close it.
                 */
                if ( currentPopup && currentPopup.getAttribute( 'focused' ) !== 'true' ) {
                    return;
                }

                /**
                 * We want to check if there is a popup that is
                 * displayed above the current one.
                 */
                const { popups }    =   nsState.state.getValue();
                const popupIndex = popups.indexOf(this.popup);
                const isTopMost = popupIndex >= 0 && popupIndex === (popups.length-1);

                // clean up motherless popup
                if (popupIndex < 0) {
                    nsHotPress.destroy( identifier );
                }

                if ( isTopMost ) {
                    if ( this.popup.params && this.popup.params.reject !== undefined ) {
                        this.popup.params.reject( false );
                    }

                    this.popup.close();
                    nsHotPress.destroy( identifier );
                }
            })
    }
}
