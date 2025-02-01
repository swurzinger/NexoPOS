declare const ns;
declare const nsHttpClient;
declare const RxJS;
class NsLanguage {
    private languages   =   {};
    private scripts     =   [];
    private callbacks   =   [];

    constructor() {
        this.loadJson();
    }

    loadJson() {
        fetch( `/lang/${ns.language}.json` )
        .then( (response) => {
            if (response.ok) {
                this.languages = response.json();
                this.loadReadyScripts();
                this.loadReadyCallbacks();
            }
        });
    }

    loadReadyScripts() {
        const scripts   =   this.scripts;
        for( let i = 0; i < scripts.length ; i++ ) {
            // get some kind of XMLHttpRequest
            // const xhrObj = new XMLHttpRequest();
            // open and send a synchronous request
            // xhrObj.open('GET', scripts[i], false);
            // xhrObj.send('');
            // add the returned content to a newly created script tag
            const script = document.createElement('script');
            script.type = "text/javascript";
            script.src  = scripts[i];
            document.body.appendChild(script);
        }
    }

    loadReadyCallbacks() {
        this.callbacks.forEach( callback => callback() );
    }

    onReadyCallback( callback ) {
        this.callbacks.push( callback );
    }

    onReadyScript( script ) {
        if ( script.length !== undefined ) {
            this.scripts.push( ...script );
        } else {
            this.scripts.push( script );
        }
    }

    getEntry(text, namespace) {
        const key = namespace ? `${namespace}.${text}` : text;
        return this.languages[key];
    }
}

( window as any ).nsLanguage         =   new NsLanguage;
