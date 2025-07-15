jQuery(document).ready((function ($) {
  'use strict'
  new Filter
}))

class Filter {

  constructor () {
    this.$parentBox = $('.js-article-wrap')

    this.$head = $('.articles--list')
    this.$sep = this.$head.find('.js-sep')

    this.$pagination = $('.pagination')

    this.$pageLinks = this.$pagination.find('.page-numbers')

    this.$reset = $('.as-b--link.js-reset')

    this.$searchWrapper = $('.form-item-search')
    this.$searchInput = this.$searchWrapper.find('input')
    this.$searchReset = this.$searchWrapper.find('.search-reset.js-reset')
    this.$spinner = this.$searchWrapper.find('.search-spinner')

    this.url = new URL(window.location)
    this.params = this.url.searchParams
    this.newUrl = ''

    this.timeout = null
    this.searchArgs = {}

    // resources page
    this.$filters = $('.js-filter')
    this.$featured = $('.js-featured-articles')
    this.visualFilters = $('.gform_wrapper__resources.gform_wrapper.gravity-theme .form-custom .chosen-container .chosen-single')


    // Stores the currently active taxonomy filters, e.g., { 'industry': ['education'], 'category': ['news'] }
    this.activeFilters = {};

    this.filtersHandler()
    this.searchHandler()
    this.paginationHandler()
    this.resetHandler()
    this.historyHandler()
  }

  filtersHandler () {
    this.$filters.on('change', (e) => {
      const $that = $(e.currentTarget)
      const filter = $that.data('filter')
      const term = $that.val()

      this.buildQueryArgs(filter, term)

      this.params.delete('pages')
      this.setURLParam(filter)

      this.getNews(args, this.$parentBox, this.$pagination)

      this.updateFilterBackground($that)

    })

    this.$filters.each((index, item) => {

      const $that = $(item)

      this.updateFilterBackground($that)

    })
  }

  buildQueryArgs (filter, term) {

    args.paged = 1

    if (term) {
      this.activeFilters[filter] = [term];
    } else {
      delete this.activeFilters[filter];
    }

    const taxQuery = Object.entries(this.activeFilters).map(([taxonomy, terms]) => {
      return {
        taxonomy: taxonomy,
        field: 'slug',
        terms: terms,
      };
    });

    if (taxQuery.length > 0) {
      args.tax_query = {
        relation: 'AND',
        ...taxQuery
      };
    }
  }

  searchHandler () {

    this.$searchInput.on('keyup', (e) => {

      e.preventDefault()

      if (this.timeout !== null) {
        clearTimeout(this.timeout)
      }

      // this.updateBackgroundColor(this.$searchInput, '#ffffff')

      //deep clone main args to searchArgs
      this.searchArgs = structuredClone(args)
      this.searchArgs.search = this.$searchInput.val()
      this.searchArgs.paged = 1

      const enter = 13

      if (this.searchArgs.search.length > 2) {

        if (e.keyCode !== enter) {

          this.timeout = setTimeout(() => {

            this.getTitles(this.searchArgs, this.$searchWrapper, this.$searchInput)

          }, 1000)

        } else {
          //deep clone searchArgs to main args if an enter key is pressed
          args = structuredClone(this.searchArgs)

          this.resetSearchResultHint()

          this.params.delete('pages')
          this.setURLParam('search')

          this.getNews(args, this.$parentBox, this.$pagination, this.$head)

        }
      }

    })

    document.addEventListener('click', (e) => {
      const searchResults = document.querySelector('.search-results')
      if(searchResults) {
        if (!searchResults.contains(e.target)) {
          this.$searchInput.val('')
          this.$searchReset.addClass('hidden')
          // this.updateBackgroundColor(this.$searchInput, '#d8d8d8')

          this.resetSearchResultHint()
        }
      }
    })

    this.updateSearchBackground(this.$searchInput)
  }

  paginationHandler () {
    this.$pageLinks.on('click', ((e) => {

      this.scrollToTarget(this.$head)

      this.ajaxPagination(e)
    }))
  }

  resetHandler () {

    this.$searchReset.on('click', (e) => {
      e.preventDefault()

      this.restAll()

      this.getNews(args, this.$parentBox, this.$pagination, this.$searchWrapper)

    })

    this.$reset.on('click', (e) => {
      e.preventDefault()

      this.restAll()

      this.$filters.each((index, item) => {
        const $that = $(item)
        $that.val('')
        const options = $that.find('option')

        options.each((i, option) => {
          const $self = $(option)
          $self.prop('disabled', false)
        })

        $that.next().removeClass('selected')
      })

      this.$filters.trigger('chosen:updated');

      this.$featured.removeClass('hidden')

      this.$parentBox.html('')
      this.$pagination.html('')

      this.$head.removeClass('block-full--pad')
      this.$sep.addClass('hidden')

      this.scrollToTarget(this.$searchWrapper)

      this.updateBackgroundColor(this.visualFilters, '#d8d8d8')

    })
  }

  historyHandler () {
    window.addEventListener('popstate', (event) => {
      location.reload()
    })
  }

  /**
   * Fetches posts from the REST API using modern vanilla JavaScript.
   *
   * @param {object} args - The WP_Query arguments object.
   * @param {HTMLElement} mainBoxEl - The DOM element to insert posts into.
   * @param {HTMLElement} paginationEl - The DOM element for pagination.
   * @param {HTMLElement|boolean} [scrollTarget=false] - The element to scroll to after loading.
   */
  async getNews(args, mainBoxEl, paginationEl, scrollTarget = false) {
    mainBoxEl.classList.add('is-loading');
    paginationEl.style.display = 'block';

    try {
      const response = await fetch(load_more_vars.api_url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          // A load_more_vars located in the Load_More component
          'X-WP-Nonce': load_more_vars.nonce
        },
        body: JSON.stringify({
          query_args: args,
          current_url: window.location.href
        })
      });

      if (!response.ok) {
        // Throw an error to be caught by the catch block
        throw new Error(`HTTP error! Status: ${response.status}`);
      }

      const data = await response.json();

      if (data && data.posts_html) {
        mainBoxEl.innerHTML = data.posts_html;
        paginationEl.innerHTML = data.pagination;
        this.hideShowFilters(data);
      } else {
        mainBoxEl.innerHTML = '<div class=""><h3>No matching results.</h3></div>';
        paginationEl.style.display = 'none';
      }

    } catch (error) {
      console.error('Failed to fetch posts:', error);
      mainBoxEl.innerHTML = `<div class=""><h3>Error: ${error.message}</h3></div>`;
      paginationEl.style.display = 'none';

    } finally {
      // This block runs after the try or catch has completed.
      mainBoxEl.classList.remove('is-loading');

      // Re-cache DOM elements and re-attach handlers
      this.paginationEl = document.querySelector('.pagination');
      if (this.paginationEl) {
        this.pageLinks = this.paginationEl.querySelectorAll('.page-numbers');
        this.paginationHandler();
      }

      // Assuming these are class properties holding DOM elements
      if (this.headEl) this.headEl.classList.add('block-full--pad');
      if (this.sepEl) this.sepEl.classList.remove('hidden');
      if (this.featuredEl) this.featuredEl.classList.add('hidden');

      if (scrollTarget) {
        this.scrollToTarget(scrollTarget);
      }
    }
  }

  getTitles (args, $mainBox, $searchInput) {
    $.ajax({

      url: `${this.url.protocol}//${this.url.hostname}/wp-json/load-more/v1/titles/?data=${JSON.stringify(args)}`,
      method: 'GET',
      dataType: 'JSON',
      beforeSend: () => {
        this.$searchWrapper.find('.search-results').remove()
        this.$spinner.removeClass('hidden')
        this.$searchReset.removeClass('hidden')
      },
      success: (response) => {

        if (response && response.titles) {

          $mainBox.append(response.titles)

        } else {
          $mainBox.append(`<div class="search-results">No results found for ${$searchInput.val()}</div>`)
        }
      },
      error: (error) => {
        console.error(error)
      },
      complete: () => {
        this.$spinner.addClass('hidden')
      }
    })
  }

  ajaxPagination (e) {
    e.preventDefault()

    const $link = $(e.currentTarget)
    const $url = $link.attr('href')

    let $page = $link.text()
    if ($page === 'next') {
      $page = parseInt(args.paged) + 1
    }
    if ($page === 'prev') {
      $page = parseInt(args.paged) - 1
    }

    args.paged = $page

    this.setURLParam('pages', $page)
    this.getNews(args, this.$parentBox, this.$pagination)

  }

  scrollToTarget (spot) {
    $('html, body').animate({
      scrollTop: spot.offset().top - 100
    }, 400)
  }

  setURLParam (item, value = '') {

    if (value) {
      this.params.set(item, value)
    } else {
      if (args[item] === null || args[item] === '') {
        this.params.delete(item)
      } else {
        this.params.set(item, args[item])
      }
    }

    this.newUrl = `${window.location.protocol}//${window.location.host}${window.location.pathname}?${this.params.toString()}`
    window.history.pushState({ path: this.newUrl }, '', this.newUrl)

    this.createCookie('filters', this.params.toString(), 365)
    this.createCookie('resources_url', `${window.location.protocol}//${window.location.host}${window.location.pathname}`, 365)
  }

  clearAllURLParams () {

    let keys = []

    this.params.forEach((value, key) => {
      keys.push(key)
    })

    keys.forEach((item) => {
      this.params.delete(item)
    })

    this.newUrl = `${window.location.protocol}//${window.location.host}${window.location.pathname}?${this.params.toString()}`
    window.history.pushState({ path: this.newUrl }, '', this.newUrl)

    this.createCookie('filters', '', 365)
    this.createCookie('resources_url', '', 365)
  }

  restAll() {
    this.$searchInput.val('')
    // this.updateBackgroundColor(this.$searchInput, '#d8d8d8')

    args.paged = 1
    args.type = ''
    args.industry = ''
    args.category = ''
    args.search = ''

    this.$searchReset.addClass('hidden')

    this.clearAllURLParams()

  }

  hideShowFilters(response) {
    this.$filters.each( (index, item) => {
      const $self = $(item)
      const $filter = $self.data('filter')
      const options = $self.find('option')


      options.each((i, option) => {
        const $that = $(option)

        if($filter === 'resource_type' && !Object.values(response.resource_type).includes($that.val())) {
          $that.prop('disabled', true)
        }

        if($filter === 'industry' && !Object.values(response.industry).includes($that.val())) {
          $that.prop('disabled', true)
        }

        if($filter === 'category' && !Object.values(response.category).includes($that.val())) {
          $that.prop('disabled', true)
        }

        if($filter === 'resource_type' && Object.values(response.resource_type).includes($that.val())) {
          $that.prop('disabled', false)
        }

        if($filter === 'industry' && Object.values(response.industry).includes($that.val())) {
          $that.prop('disabled', false)
        }

        if($filter === 'category' && Object.values(response.category).includes($that.val())) {
          $that.prop('disabled', false)
        }
      })

      $self.trigger('chosen:updated');

    })
  }

  resetSearchResultHint() {
    this.searchArgs = {}
    this.$searchWrapper.find('.search-results').remove()
  }

  updateBackgroundColor(item, color) {
    item.css('background-color', color);
  }

  updateFilterBackground(item) {

    if(item.val() !== '') {
      this.updateBackgroundColor(item.closest('.form-item').find('.chosen-single'), '#ffffff')
    }

    if(item.val() === null || item.val() === '') {
      this.updateBackgroundColor(item.closest('.form-item').find('.chosen-single'), '#d8d8d8')
    }
  }

  updateSearchBackground(item) {
    if(item.val() !== '') {
      this.updateBackgroundColor(item, '#ffffff')
    }
  }

  createCookie(name,value,days) {
    let expires;
    if (days) {
      let date = new Date();
      date.setTime(date.getTime()+(days*24*60*60*1000));
      expires = "; expires="+date.toGMTString();
    }
    else expires = "";
    const url = new URL(window.location);
    const domain = url.hostname;
    document.cookie = name+"="+value+expires+"; path=/; domain=." + domain;
  }
}
