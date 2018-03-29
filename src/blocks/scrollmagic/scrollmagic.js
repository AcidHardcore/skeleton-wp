jQuery(document).ready(function ($) {

    // Init ScrollMagic
    var controller = new ScrollMagic.Controller();

    // Pin the intro
    var pinIntroScene = new ScrollMagic.Scene({
        triggerElement: '.hero',
        triggerHook: 0,
        duration: '100%'
    })
        .setPin('.hero', {pushFollowers: false})
        .addTo(controller);

// parallax scene calc-price

    var parallaxTl = new TimelineMax();
    parallaxTl
        .from('.calc-price__container', 0.4, {autoAlpha: 0, ease: Power0.easeNone}, 0.4)
        .from('.calc-price__background', 2, {y: '-50%', ease: Power0.easeNone}, 0);

    var slideParallaxScene = new ScrollMagic.Scene({
        triggerElement: '.calc-price',
        triggerHook: 1,
        duration: '100%'
    })
        .setTween(parallaxTl)
        .addTo(controller);

    // parallax scene consultation

    var parallaxTl2 = new TimelineMax();
    parallaxTl2
        .from('.consultation__container', 0.4, {autoAlpha: 0, ease: Power0.easeNone}, 0.4)
        .from('.consultation__background', 2, {y: '-50%', ease: Power0.easeNone}, 0);

    var slideParallaxScene = new ScrollMagic.Scene({
        triggerElement: '.consultation',
        triggerHook: 1,
        duration: '100%'
    })
        .setTween(parallaxTl2)
        .addTo(controller);

    // move scene warranty

    var moveTl = new TimelineMax();
    moveTl
        .from('.warranty__item-small--left-move', 1, {
            x: '-50%',
            y: '-100%',
            autoAlpha: 0,
            rotation: 30,
            scaleX: 0.8,
            ease: Power0.easeNone
        }, 0)
        .from('.warranty__item-small--right-move', 1, {
            x: '50%',
            y: '-100%',
            autoAlpha: 0,
            rotation: -30,
            scaleX: 0.8,
            ease: Power0.easeNone
        }, 0)
        .from('.warranty__img--right-rotate', 1, {rotationY: 360, transformOrigin: "right top"}, 0);

    var moveScene = new ScrollMagic.Scene({
        triggerElement: '.warranty',
        triggerHook: 1,
        duration: '100%',
        reverse: false
    })
        .setTween(moveTl)
        .addTo(controller);

    //rotate services top triangles

    var rotateTl = new TimelineMax();
    rotateTl
        .from('.service__triangle-top-left', 1, {
            x: '-50%',
            y: '-100%',
            autoAlpha: 0,
            rotation: 30,
            scaleX: 0.8,
            ease: Power0.easeNone
        }, 0)
        .from('.service__triangle-top-right', 1, {
            x: '50%',
            y: '-100%',
            autoAlpha: 0,
            rotation: -30,
            scaleX: 0.8,
            ease: Power0.easeNone
        }, 0);

    var rotateScene = new ScrollMagic.Scene({
        triggerElement: '.service',
        triggerHook: 1,
        duration: '100%',
        reverse: false
    })
        .setTween(rotateTl)
        .addTo(controller);

    //rotate services bottom triangles

    var rotateTl2 = new TimelineMax();
    rotateTl2
        .from('.service__triangle-bottom-left', 1, {
            x: '-50%',
            y: '100%',
            autoAlpha: 0,
            rotation: 30,
            scaleX: 0.8,
            ease: Power0.easeNone
        }, 0)
        .from('.service__triangle-bottom-right', 1, {
            x: '50%',
            y: '100%',
            autoAlpha: 0,
            rotation: -30,
            scaleX: 0.8,
            ease: Power0.easeNone
        }, 0);

    var rotateScene2 = new ScrollMagic.Scene({
        triggerElement: '.service',
        triggerHook: .5,
        duration: '100%',
        reverse: false
    })
        .setTween(rotateTl2)
        .addTo(controller);


// loop through each .project element

    $('.list-steps__item').each(function () {

        // build a scene
        var ourScene = new ScrollMagic.Scene({
            triggerElement: this.children[0],
            triggerHook: 0.9,
            reverse: false
        })
            .setClassToggle(this, 'list-steps__item--fade-in')
            .addTo(controller);

    });

    //show advantages one by one

    $('.about__item').each(function () {

        // build a scene
        var ourScene2 = new ScrollMagic.Scene({
            triggerElement: this.children[0],
            triggerHook: 0.9,
            reverse: false
        })
            .setClassToggle(this, 'about__item--fade-in')
            .addTo(controller);

    });


});
