/*! skeleton_WP v3.0.0 | (c) 2022  |  License | https://github.com/AcidHardcore/skeleton_WP */

document.addEventListener('DOMContentLoaded', (function(){

  if(location.hash) {
    showTab(location.hash);
  }

  // track for the next clicks
  document.addEventListener('click', (function(event) {
    if(event.target.dataset.toggle === 'tab') {
      event.preventDefault();
      var target = event.target.hash === undefined ? event.target.dataset.target : event.target.hash;
      if ( target !== undefined ) {
        showTab(target);
        if(history && history.pushState && history.replaceState) {
          var stateObject = {'url' : target};
          if (window.location.hash && stateObject.url !== window.location.hash) {
            window.history.pushState(stateObject, document.title, window.location.pathname + target);
          } else {
            window.history.replaceState(stateObject, document.title, window.location.pathname + target);
          }
        }
      }
    }
  }));

  /**
   * Show tab
   * @param  {string} tabId ID of tab, that needs ty show
   */
  function showTab(tabId){
    var element = document.querySelector(tabId);
    if ( element && element.classList.contains('tabs__content-item') ) {
      var tabsParent = document.querySelector(tabId).closest('.tabs');
      var activeTabClassName = 'tabs__link-wrap--active';
      var activeTabContentClassName = 'tabs__content-item--active';
      // tab
      tabsParent.querySelectorAll('.'+activeTabClassName).forEach((function(item){
        item.classList.remove(activeTabClassName);
      }));
      var activeTab = tabsParent.querySelector('[href="'+tabId+'"]') ? tabsParent.querySelector('[href="'+tabId+'"]') : tabsParent.querySelector('[data-target="'+tabId+'"]');
      activeTab.closest('.tabs__link-wrap').classList.add(activeTabClassName);
      // tab content
      tabsParent.querySelectorAll('.'+activeTabContentClassName).forEach((function(item){
        item.classList.remove(activeTabContentClassName);
      }));
      tabsParent.querySelector(tabId).classList.add(activeTabContentClassName);
    }
  }

  // .closest() (polyfill)
  (function(e){
    e.closest = e.closest || function(css){
      var node = this;
      while (node) {
        if (node.matches(css)) {
        	return node;
        }
        else {
			node = node.parentElement;
		}
      }
      return null;
    };
  })(Element.prototype);

}));
