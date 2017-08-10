(function () {
    const elem = document.querySelector('.slider__container');
    if (elem) {
        const flkty = new Flickity(elem, {
            contain: true,
            wrapAround: true,
            setGallerySize: false,
            imagesLoaded: true
            // percentPosition:true,
            // cellSelector: '.slider-cell'
        });
    }
})();


