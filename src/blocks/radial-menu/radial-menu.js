// document.addEventListener('DOMContentLoaded', function(){});
// (function(){
// код
// }());
jQuery(document).ready(function ($) {
    $(".btn--trigger").click(function() {
        $(".radial-menu").toggleClass("radial-menu--active");
    });

    $(".btn--icon").click(function () {
        console.log("link");
    });

    $(".radial-menu__rotater").click(function () {
        console.log("rotator");
    });
});