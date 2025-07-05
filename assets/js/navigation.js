/* global skeletonWpScreenReaderText, gsap */
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
 * - GSAP animation for mobile menu toggle (mobile-only).
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
 * This function now includes GSAP animations that only run on mobile viewports.
 * @param {HTMLElement} nav - The navigation element.
 */
function initEachNavToggleSmall(nav) {
  const menuToggle = nav.querySelector('.main-nav__toggle');
  const siteHeader = document.querySelector('.site-header');
  const menuItems = nav.querySelectorAll('.main-nav--toggle-small li');
  const menuContainer = nav.querySelector('.menu');
  const mediaQuery = window.matchMedia('(max-width: 992px)'); // check $menu-desktop-width for consistency

  if (!menuToggle) return;

  // Fallback if GSAP is not loaded
  if (typeof gsap === 'undefined') {
    console.warn('GSAP not found. Animations will be disabled.');
    menuToggle.setAttribute('aria-expanded', 'false');
    menuToggle.addEventListener('click', (e) => {
      toggleMenu(e.currentTarget);
    }, false);
    return;
  }

  let menuTimeline;

  function initializeGsapAnimation() {
    if (menuTimeline) return;

    gsap.set(menuContainer, {
      height: 0,
      opacity: 0,
      display: 'none'
    });

    gsap.set(menuItems, {
      opacity: 0.5,
      x: -20,
      rotationY: -90,
      transformOrigin: '0% 50%',
    });

    menuTimeline = gsap.timeline({ paused: true })
      .to(menuContainer, {
        height: 'auto',
        opacity: 1,
        display: 'block',
        duration: 0.3,
        ease: 'power2.inOut'
      })
      .to(menuItems, {
        opacity: 1,
        x: 0,
        rotationY: 0,
        duration: 0.5,
        ease: 'power3.out',
        stagger: 0.08
      }, '<0.2');

    menuTimeline.reverse(0);
  }

  function toggleMenu(toggleButton = menuToggle) {
    const isExpanding = !nav.classList.contains('main-nav--toggled-on');

    nav.classList.toggle('main-nav--toggled-on');
    siteHeader.classList.toggle('site-header--menu-open');
    toggleButton.classList.toggle('burger--close');
    toggleButton.setAttribute('aria-expanded', isExpanding);

    if (mediaQuery.matches) {
      initializeGsapAnimation();

      if (isExpanding) {
        menuTimeline.play();
        // Add click outside listener when menu opens
        document.addEventListener('click', handleClickOutside);
      } else {
        gsap.delayedCall(0.1, () => {
          menuTimeline.reverse();
          // Remove click outside listener when menu closes
          document.removeEventListener('click', handleClickOutside);
        });
      }
    }
  }

  function handleClickOutside(event) {
    // Check if click is outside the nav and menu is open
    if (nav.classList.contains('main-nav--toggled-on') &&
      !nav.contains(event.target) &&
      event.target !== menuToggle) {
      toggleMenu();
    }
  }

  // Initialize
  menuToggle.setAttribute('aria-expanded', 'false');
  menuToggle.addEventListener('click', (e) => toggleMenu(e.currentTarget), false);

  // Close menu when ESC key is pressed
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && nav.classList.contains('main-nav--toggled-on')) {
      toggleMenu();
    }
  });
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
