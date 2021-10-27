$(document).ready(function() {
// select the overlay element - and "make it an overlay"
$("#facebox").overlay({
 
    // custom top position
    top: 160,
    // some mask tweaks suitable for facebox-looking dialogs
    mask: {
        // you might also consider a "transparent" color for the mask
        color: '#3e1a0a',
        // load mask a little faster
        loadSpeed: 1000,
        // very transparent
        opacity: 0.7
    },
    // disable this for modal dialog-type of overlays
    closeOnClick: true,
    // we want to use the programming API
    api: true
 
// load it after delay
})
var ol = $("#facebox").overlay({api: true});
// wait 5 seconds, then load the overlay
 setTimeout(function() {
   ol.load();
 }, 500);
setTimeout(function() {
    ol.close();
 }, 7000);
});