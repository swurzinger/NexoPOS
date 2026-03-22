import { NsHotPress } from "~/libraries/ns-hotpress";
import { State } from "~/libraries/state";
import { Popup } from "~/libraries/popup";

declare const nsHotPress : NsHotPress;
declare const nsState: State;

/**
 * Must be used on component
 * that has the popup object defined.
 */
export default function() {
    if ( this.popup !== undefined ) {
        const identifier = `popup-esc-${this.popup.hash}`;
        /**
         * We'll listen to "esc" keypress
         * but proceed in certain conditions.
         */
        nsHotPress.create( identifier )
            .whenPressed( 'escape', ( event: Event ) => {
                event.preventDefault();

                /**
                 * We want to check if there is a popup that is
                 * displayed above the current one.
                 */
                const { popups }    =   <{ popups: Popup[]}>nsState.state.getValue();
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