(function($){
  $(document).ready(function(){
    let stp = 0;
    $(window).scroll(function() {
      let st = $(window).scrollTop();
      const $body = $('body');
      let $hh = $('.header');
      if ( st > 50) {
        $body.addClass('body--header-smaller');
      } else {
        $body.removeClass('body--header-smaller');
      }
      if ( Math.abs(st - stp) > 2 ) {
        if ( st > 50 && ( st - stp ) > 0 ) {
          $body.addClass('body--header-scrolled');
        } else {
          $body.removeClass('body--header-scrolled');
        }
      }
      stp = st;
    });
  });
})(jQuery);
