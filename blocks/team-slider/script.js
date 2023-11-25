(function ($) {
  'use strict'

  /**
   * initializeBlock
   *
   * Adds custom JavaScript to the block HTML.
   *
   *
   * @param   object $block The block jQuery element.
   * @param   object attributes The block attributes (only available when editing).
   * @return  void
   */

  class TeamSlider {
    constructor($w) {
      const _this = this;

      this.$wrap = $w;
      this.$slider = this.$wrap.find('.team-slider__slider');
      this.$cta_more = this.$wrap.find('.team-slider__show-all');
      this.$cta_less = this.$wrap.find('.team-slider__show-less');
      this.$slides = this.$slider.find('.team-slider__member');
      this.$hidden = $('<div class="hidden"></div>');
      this.$wrap.append(this.$hidden);

      this.close(1);

      this.$cta_more.on('click', function(e) {
        e.preventDefault();
        _this.open();
      });
      this.$cta_less.on('click', function(e) {
        e.preventDefault();
        _this.close();
      })
    }

    close( no_anim ) {
      let $detached = this.$slides.slice(5).detach();
      this.$hidden.append($detached);

      this.$wrap.removeClass('team-slider--open');

      this.$slider.slick({
        dots: true,
        arrows: true,
        prevArrow: '<div class="slider-arrow slider-arrow--prev"></div>',
        nextArrow: '<div class="slider-arrow slider-arrow--next"></div>',
        appendArrows: this.$wrap.find('.team-slider__wrap'),
        speed: 500,
        slidesToShow: 1,
        swipeToSlide: true,
        centerMode: true,
        infinite: true,
        adaptiveHeight: false,
        autoplay: false,
        autoplaySpeed: 4000,
        pauseOnHover: false,
        responsive: [
          {
            breakpoint: 1024,
            settings: {
              slidesToShow: 1,
            }
          },
        ]
      });

      if ( !no_anim ) {
        scrollto(this.$wrap);
      }
    }

    // Irreversible
    open() {
      const _this = this;
      //this.$slider.slick('slickGoTo', is_mobile() ? 0 : 1);
      setTimeout(function() {
        _this.$wrap.addClass('team-slider--open');
        _this.$slider.slick('unslick');
        let $detached = _this.$hidden.children().detach();
        _this.$slider.append($detached);
        _this.$slides.slice(2).css({opacity: 0}).animate({
          opacity: 1,
        }, {
          duration: 500,
        })
      }, 100);
    }

  }
  var initializeBlock = function ($block) {
    $('.team-slider').once('sw-team-slider').each(function() {
      const ts = new TeamSlider($(this));
    });
  }

  // Initialize each block on page load (front end).
  $(document).ready(function () {
    initializeBlock($(this))
  })

  // Initialize dynamic block preview (editor).
  if (window.acf) {
    window.acf.addAction('render_block_preview/type=team-slider', initializeBlock)
  }

})(jQuery)

