/*! skeleton_WP v3.0.0 | (c) 2021  |  License | https://github.com/AcidHardcore/skeleton_WP */
jQuery(document).ready((function ($) {
	"use strict";
	/**
	 * @typedef jsData
	 * @type {object}
	 * @property {string} ajaxUrl - an admin AJAX URL.
	 */


	let $mainBox = $('.posts-wrap');
	let $pagination = $('.navigation');
	let $loadMore = $('.load-more');
	const url = new URL(window.location);

	if ($pagination.length) {
		let $pageLinks = $pagination.find('.page-link');
		let $args = $pagination.data('args');

		let ajaxLoadMore = function (e, $self) {
			e.preventDefault();
			//if need only current target
			let $link = $self;
			let $url = $link.attr('href');
			let $page = $link.text();
			console.log($url);
			console.log('$args.paged ', $args.paged);
			if ($page === 'Next') {
				$page = parseInt($args.paged) + 1;
			}
			if ($page === 'Previous') {
				$page = parseInt($args.paged) - 1;
			}
			console.log('current ', $page);

			$args.action = 'load_more_button';
			$args.paged = $page;

			$.ajax({

				url: `${url.protocol}//${url.hostname}/wp-json/load-more/v1/posts/?data=${JSON.stringify($args)}`,
				method: 'GET',
				dataType: 'JSON',
				beforeSend: function () {
					$mainBox.animate({opacity: 0.5}, 300);
				},
				success: function (response) {

					if (response.posts) {

						$mainBox.html(response.posts).animate({opacity: 1}, 300); // insert new posts
						$pagination.find('ul').html(response.pagination).animate({opacity: 1}, 300);

						$args.paged = $page;

					} else {
						console.log('pagination: no AJAX data');
					}
				},
				complete: function () {
					let $pagination = $('.navigation');
					let $pageLinks = $pagination.find('.page-link');
					$pageLinks.on('click', (function (e) {
						$self = $(this);
						ajaxLoadMore(e, $self);
					}));

				}
			});
			return false;
		};

		$pageLinks.on('click', (function (e) {
			let $self = $(this);
			ajaxLoadMore(e, $self);
		}));
	}

	if ($loadMore.length) {

		let $args = $loadMore.data('args');
		$args.action = 'load_more_button';
		$args.paged += 1; //increment PAGED

		/*
		 * Load More
		 */
		$loadMore.on('click', (function (e) {
			e.preventDefault();

			$.ajax({

				url: `${url.protocol}//${url.hostname}/wp-json/load-more/v1/posts/?data=${JSON.stringify($args)}`,
				method: 'GET',
				dataType: 'JSON',
				beforeSend: function () {
					$mainBox.animate({opacity: 0.5}, 300);
					$loadMore.attr('disabled', true);
				},
				success: function (response) {
					// console.log(data, status, response.responseJSON); return;
					if (response) {
						$loadMore.attr('disabled', false);
						$mainBox.append(response.posts).animate({opacity: 1}, 300); // insert new posts

						$args.paged++;

						if ($args.paged === $args.max_page) {
							// if last page, HIDE the button
							$loadMore.addClass('hidden');
						}

					} else {
						// if no data, HIDE the button as well
						$mainBox.animate({opacity: 1}, 300); // insert new posts
						$loadMore.addClass('hidden');
					}
				}
			});
			return false;
		}));
	}
}));
