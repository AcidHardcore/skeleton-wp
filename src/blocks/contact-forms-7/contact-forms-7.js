// document.addEventListener('DOMContentLoaded', function(){});
jQuery(document).ready(function ($) {
    var wpcf7Html = document.querySelector('#modal-html .wpcf7');

    if (wpcf7Html) {
        wpcf7Html.addEventListener('wpcf7mailsent', function (event) {
            $('#modal-html').modal('hide');
            $('#modal-submit').modal('show');
            // $('.wpcf7-mail-sent-ok').css({'display': 'none'});
        }, false);
    }

    var wpcf7Wordpress = document.querySelector('#modal-wordpress .wpcf7');

    if (wpcf7Wordpress) {
        wpcf7Wordpress.addEventListener('wpcf7mailsent', function (event) {
            $('#modal-wordpress').modal('hide');
            $('#modal-submit').modal('show');
            // $('.wpcf7-mail-sent-ok').css({'display': 'none'});
        }, false);
    }

    var wpcf7Speed = document.querySelector('#modal-speed .wpcf7');

    if (wpcf7Speed) {
        wpcf7Speed.addEventListener('wpcf7mailsent', function (event) {
            $('#modal-speed').modal('hide');
            $('#modal-submit').modal('show');
            // $('.wpcf7-mail-sent-ok').css({'display': 'none'});
        }, false);
    }

    var wpcf7Landing = document.querySelector('#modal-landing .wpcf7');

    if (wpcf7Landing) {

        wpcf7Landing.addEventListener('wpcf7mailsent', function (event) {
            $('#modal-landing').modal('hide');
            $('#modal-submit').modal('show');
            // $('.wpcf7-mail-sent-ok').css({'display': 'none'});
        }, false);
    }

    var wpcf7Website = document.querySelector('#modal-website .wpcf7');

    if (wpcf7Website) {
        wpcf7Website.addEventListener('wpcf7mailsent', function (event) {
            $('#modal-website').modal('hide');
            $('#modal-submit').modal('show');
            // $('.wpcf7-mail-sent-ok').css({'display': 'none'});
        }, false);
    }

    var wpcf7Calc = document.querySelector('#modal-calc .wpcf7');

    if (wpcf7Calc) {

        wpcf7Calc.addEventListener('wpcf7mailsent', function (event) {
            $('#modal-calc').modal('hide');
            $('#modal-submit').modal('show');
            // $('.wpcf7-mail-sent-ok').css({'display': 'none'});
        }, false);
    }
    // wpcf7Elm.addEventListener('wpcf7submit', function (event) {
    //
    //     }, false);
});
