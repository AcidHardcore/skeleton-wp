//position:fixed on scroll
(function () {
    const nav = document.querySelector('#wrapper-navbar');
    const topNav = nav.offsetTop;

    function fixNav() {
        if (window.scrollY > topNav) {
            nav.nextElementSibling.style.paddingTop = nav.offsetHeight + 'px';
            nav.classList.add('fixed-top');
        } else {
            nav.nextElementSibling.style.paddingTop = 0;
            nav.classList.remove('fixed-top');
        }
    }

    window.addEventListener('scroll', fixNav);
}());
