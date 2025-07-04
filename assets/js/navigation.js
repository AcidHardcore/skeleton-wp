/* global skeletonWpScreenReaderText */
/**
 * File navigation.js.
 *
 * Handles toggling the navigation menu for small screens and enables TAB key
 * navigation support for dropdown menus.
 *
 * This refactored version includes:
 * - A generic initialization function to reduce code duplication.
 * - Modern forEach loops for better readability.
 * - Improved submenu closing logic using 'focusout' for better accessibility and reliability.
 * - Enhanced accessibility with `aria-controls`.
 * - Refactored toggleSubMenu function for better clarity and maintainability.
 */

const KEYMAP = {
  TAB: 9,
};

/**
 * Run the initialization functions when the DOM is ready.
 */
if ('loading' === document.readyState) {
  // The DOM has not yet been loaded.
  document.addEventListener('DOMContentLoaded', init);
} else {
  // The DOM has already been loaded.
  init();
}

/**
 * Main initialization function.
 */
function init() {
  initAllNavs('.main-nav--toggle-sub', initEachNavToggleSubmenu);
  initAllNavs('.main-nav--toggle-small', initEachNavToggleSmall);
}

/**
 * A generic helper function to initialize all navigation elements matching a selector.
 * @param {string} selector - The CSS selector for the navigation containers.
 * @param {Function} callback - The function to call for each found navigation element.
 */
function initAllNavs(selector, callback) {
  const navs = document.querySelectorAll(selector);
  navs.forEach(nav => callback(nav));
}

/**
 * Initiate the script to process submenu navigation toggle for a specific navigation menu.
 * @param {HTMLElement} nav - The navigation element.
 */
function initEachNavToggleSubmenu(nav) {
  const submenus = nav.querySelectorAll('.menu ul');
  if (!submenus.length) {
    return;
  }

  // Add a focusout listener to the entire navigation menu for robust closing.
  nav.addEventListener('focusout', (e) => {
    // If the element that is receiving focus is NOT inside this nav, close all submenus.
    if (!nav.contains(e.relatedTarget)) {
      nav.querySelectorAll('.menu-item--toggled-on').forEach(item => {
        closeSubMenu(item);
      });
    }
  });

  submenus.forEach((submenu, i) => {
    const parentMenuItem = submenu.parentNode;
    const dropdownToggle = parentMenuItem.querySelector('.dropdown-toggle');
    const menuLink = parentMenuItem.querySelector('a');

    // Assign a unique ID to the submenu if it doesn't have one, for aria-controls.
    if (!submenu.id) {
      submenu.id = `submenu-${nav.className.split(' ')[0]}-${i}`;
    }
    dropdownToggle.setAttribute('aria-controls', submenu.id);

    // Toggle the submenu when we click the dropdown button.
    dropdownToggle.addEventListener('click', () => {
      toggleSubMenu(parentMenuItem);
    });

    // When we focus on a menu link, make sure all sibling submenus are closed.
    menuLink.addEventListener('focus', () => {
      const parentContainer = parentMenuItem.parentNode;
      parentContainer.querySelectorAll('.menu-item--toggled-on').forEach(item => {
        if (item !== parentMenuItem) {
          closeSubMenu(item);
        }
      });
    });

    // Handle keyboard accessibility for traversing the submenu.
    submenu.addEventListener('keydown', (e) => {
      if (KEYMAP.TAB !== e.keyCode) {
        return;
      }

      // Get all focusable elements within the current open submenu.
      const focusableElements = submenu.querySelectorAll('a, button');
      const firstFocusable = focusableElements[0];
      const lastFocusable = focusableElements[focusableElements.length - 1];

      // If shift-tabbing from the first element, close the submenu.
      if (e.shiftKey && document.activeElement === firstFocusable) {
        closeSubMenu(parentMenuItem);
      }

      // If tabbing from the last element, close the submenu.
      if (!e.shiftKey && document.activeElement === lastFocusable) {
        closeSubMenu(parentMenuItem);
      }
    });

    parentMenuItem.classList.add('menu-item--has-toggle');
  });
}

/**
 * Initiate the script to process the small navigation toggle for a specific navigation menu.
 * @param {HTMLElement} nav - The navigation element.
 */
function initEachNavToggleSmall(nav) {
  const menuToggle = nav.querySelector('.main-nav__toggle');
  const siteHeader = document.querySelector('.site-header');

  if (!menuToggle) {
    return;
  }

  menuToggle.setAttribute('aria-expanded', 'false');

  menuToggle.addEventListener('click', (e) => {
    nav.classList.toggle('main-nav--toggled-on');
    siteHeader.classList.toggle('site-header--menu-open');
    menuToggle.classList.toggle('burger--close');
    const isExpanded = 'true' === e.currentTarget.getAttribute('aria-expanded');
    e.currentTarget.setAttribute('aria-expanded', !isExpanded);
  }, false);
}

/**
 * Opens a submenu and ensures sibling menus are closed.
 * @param {HTMLElement} parentMenuItem - The parent li element of the submenu to open.
 */
function openSubMenu(parentMenuItem) {
  // Close any sibling menus that are open.
  const parentContainer = parentMenuItem.parentNode;
  parentContainer.querySelectorAll('.menu-item--toggled-on').forEach(item => {
    if (item !== parentMenuItem) {
      closeSubMenu(item);
    }
  });

  const toggleButton = parentMenuItem.querySelector('.dropdown-toggle');
  const subMenu = parentMenuItem.querySelector('ul');

  parentMenuItem.classList.add('menu-item--toggled-on');
  subMenu.classList.add('toggle-show');
  toggleButton.setAttribute('aria-expanded', 'true');
  toggleButton.setAttribute('aria-label', skeletonWpScreenReaderText.collapse);
}

/**
 * Closes a submenu and any of its open children.
 * @param {HTMLElement} parentMenuItem - The parent li element of the submenu to close.
 */
function closeSubMenu(parentMenuItem) {
  const toggleButton = parentMenuItem.querySelector('.dropdown-toggle');
  const subMenu = parentMenuItem.querySelector('ul');

  parentMenuItem.classList.remove('menu-item--toggled-on');
  subMenu.classList.remove('toggle-show');
  toggleButton.setAttribute('aria-expanded', 'false');
  toggleButton.setAttribute('aria-label', skeletonWpScreenReaderText.expand);

  // Ensure all children submenus are also closed.
  parentMenuItem.querySelectorAll('.menu-item--toggled-on').forEach(item => closeSubMenu(item));
}

/**
 * Toggles a submenu's state, dispatching to open or close functions.
 * @param {HTMLElement} parentMenuItem - The parent menu item element.
 */
function toggleSubMenu(parentMenuItem) {
  const isToggledOn = parentMenuItem.classList.contains('menu-item--toggled-on');
  if (isToggledOn) {
    closeSubMenu(parentMenuItem);
  } else {
    openSubMenu(parentMenuItem);
  }
}
