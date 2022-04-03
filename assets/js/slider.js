/*! skeleton_WP v3.0.0 | (c) 2022  |  License | https://github.com/AcidHardcore/skeleton_WP */
(function($){
	"use strict";
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
	var initializeBlock = function( $block ) {

		const swiper = new Swiper('.block-slider .swiper', {
			direction: 'horizontal',
			loop: true,
			autoplay: {
				delay: 5000,
			pauseOnMouseEnter: true
			},
			pagination: {
				el: '.swiper-pagination',
				clickable: true
			},

		});

	};

	// Initialize each block on page load (front end).
	$(document).ready((function(){
		$('.block-slider').each((function(){
			initializeBlock( $(this) );
		}));
	}));

	// Initialize dynamic block preview (editor).
	if( window.acf ) {
		window.acf.addAction( 'render_block_preview', initializeBlock );
	}

})(jQuery);

