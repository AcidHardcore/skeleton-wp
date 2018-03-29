Disable auto p and br in Contact form 7 */ define( 'WPCF7_AUTOP', false );

DOM Events
Actions after Submit
https://contactform7.com/dom-events/

List of Contact Form 7 Custom DOM Events

wpcf7invalid — Fires when an Ajax form submission has completed successfully, but mail hasn’t been sent because there are fields with invalid input.
wpcf7spam — Fires when an Ajax form submission has completed successfully, but mail hasn’t been sent because a possible spam activity has been detected.
wpcf7mailsent — Fires when an Ajax form submission has completed successfully, and mail has been sent.
wpcf7mailfailed — Fires when an Ajax form submission has completed successfully, but it has failed in sending mail.
wpcf7submit — Fires when an Ajax form submission has completed successfully, regardless of other incidents.

var wpcf7Elm = document.querySelector( '.wpcf7' );
 
wpcf7Elm.addEventListener( 'wpcf7submit', function( event ) {
    alert( "Fire!" );
}, false );