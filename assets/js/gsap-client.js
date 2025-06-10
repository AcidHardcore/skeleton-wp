class GsapEffects {
  constructor() {
    if (!gsap.effects) {
      gsap.effects = {};
    }

    this.animationPresets = {
      baseConfig: {
        duration: 0.5,
        stagger: 0,
        ease: 'power2.out'
      },
      effects: [
        {
          id: 'fadeSlideTo',
          type: 'to',
          props: { opacity: 0.5, x: 300, yoyo: true, repeat: -1 }
        },
        {
          id: 'fadeSlideFrom',
          type: 'from',
          props: { opacity: 0.25, x: 300, yoyo: true, repeat: -1 }
        },
        {
          id: 'fadeSlideFromTo',
          type: 'fromTo',
          fromProps: { opacity: 0.1, x: 300 },
          toProps: { opacity: 1, x: 600, yoyo: true, repeat: -1 }
        },
        {
          id: 'fadeIn',
          type: 'fromTo',
          fromProps: { opacity: 0 },
          toProps: { opacity: 1 }
        },
        {
          id: 'fadeUp',
          type: 'fromTo',
          fromProps: { opacity: 0, y: 50 },
          toProps: { opacity: 1, y: 0 }
        },
        {
          id: 'fadeLeft',
          type: 'fromTo',
          fromProps: { opacity: 0, x: -50 },
          toProps: { opacity: 1, x: 0 }
        },
        {
          id: 'fadeRight',
          type: 'fromTo',
          fromProps: { opacity: 0, x: 50 },
          toProps: { opacity: 1, x: 0 }
        },
        {
          id: 'splitText',
          type: 'custom',
          config: {
            charDuration: 0.5,
            charStagger: 0.05,
            yOffset: 20
          }
        }
      ]
    };

    this.registerEffects();
  }

  registerEffects() {
    this.animationPresets.effects.forEach(effect => {
      gsap.effects[effect.id] = (targets, config) => {
        if (effect.id === 'splitText') {
          return this.handleSplitTextAnimation(targets, config, effect);
        }

        const mergedConfig = { ...this.animationPresets.baseConfig, ...config };

        switch (effect.type) {
          case 'from':
            return gsap.from(targets, { ...effect.props, ...mergedConfig });
          case 'to':
            return gsap.to(targets, { ...effect.props, ...mergedConfig });
          case 'fromTo':
            return gsap.fromTo(
              targets,
              { ...effect.fromProps, ...mergedConfig },
              { ...effect.toProps, ...mergedConfig }
            );
          default:
            console.warn(`Unknown animation type: ${effect.type}`);
            return gsap.to(targets, { ...mergedConfig });
        }
      };
    });
  }

  handleSplitTextAnimation(targets, config, effect) {
    const targetElement = this.getValidElement(targets);
    if (!targetElement) {
      console.error('SplitText: Invalid target element');
      return gsap.timeline();
    }

    try {
      const split = new SplitText(targetElement, { type: 'chars' });
      const tl = gsap.timeline();

      const duration = config.duration || effect.config.charDuration;
      const stagger = config.stagger || effect.config.charStagger;

      tl.from(split.chars, {
        opacity: 0,
        y: effect.config.yOffset,
        duration,
        ease: config.ease || this.animationPresets.baseConfig.ease,
        stagger
      });

      if (config.revertOnComplete) {
        tl.eventCallback('onComplete', () => split.revert());
      }

      return tl;
    } catch (error) {
      console.error('SplitText animation failed:', error);
      return gsap.timeline();
    }
  }

  getValidElement(targets) {
    if (targets instanceof Element) return targets;
    if (targets?.[0] instanceof Element) return targets[0];
    return null;
  }
}

class ScrollTriggerAnimator {
  constructor() {
    this.DEFAULT_SCROLL_START = 'top 80%';
    this.DEFAULT_SCROLL_END = 'bottom 20%';
    this.DEFAULT_TOGGLE_ACTIONS = 'play none none none';
    this.GLOBAL_OVERLAP = '-=0.5';
  }

  init() {
    const sections = document.querySelectorAll('[data-scroll-section]');
    sections.forEach(section => this.processSection(section));
  }

  processSection(section) {
    const sectionStart = section.getAttribute('data-scroll-start') || this.DEFAULT_SCROLL_START;
    const sectionEnd = section.getAttribute('data-scroll-end') || this.DEFAULT_SCROLL_END;
    const sectionToggleActions = section.getAttribute('data-scroll-toggle') || this.DEFAULT_TOGGLE_ACTIONS;

    const tl = gsap.timeline({
      scrollTrigger: {
        trigger: section,
        start: sectionStart,
        end: sectionEnd,
        toggleActions: sectionToggleActions,
        markers: false
      },
    });

    const items = section.querySelectorAll('[data-scroll-item], [data-scroll-stagger-group]');
    const orderedItems = this.sortItemsByOrder(Array.from(items));

    orderedItems.forEach((item, index) => this.processItem(item, index, tl));
  }

  sortItemsByOrder(items) {
    return items.sort((a, b) => {
      const orderA = parseInt(a.getAttribute('data-scroll-order') || '0');
      const orderB = parseInt(b.getAttribute('data-scroll-order') || '0');
      return orderA - orderB;
    });
  }

  processItem(item, index, timeline) {
    const isStaggerGroup = item.hasAttribute('data-scroll-stagger-group');
    const isIndependent = item.getAttribute('data-scroll-independent') === 'true';

    if (isIndependent) {
      this.createIndependentAnimation(item);
    } else {
      this.addToTimeline(item, index, timeline, isStaggerGroup);
    }
  }

  createIndependentAnimation(item) {
    const animationType = item.getAttribute('data-scroll-animation') || 'fadeUp';
    const duration = parseFloat(item.getAttribute('data-scroll-duration')) || 0.5;
    const stagger = parseFloat(item.getAttribute('data-scroll-stagger')) || 0.1;
    const delay = parseFloat(item.getAttribute('data-scroll-delay')) || 0;
    const itemStart = item.getAttribute('data-scroll-start') || this.DEFAULT_SCROLL_START;
    const itemEnd = item.getAttribute('data-scroll-end') || this.DEFAULT_SCROLL_END;
    const itemToggleActions = item.getAttribute('data-scroll-toggle') || this.DEFAULT_TOGGLE_ACTIONS;

    if (!gsap.effects[animationType]) {
      console.warn(`Animation effect "${animationType}" not found`);
      return;
    }

    const independentTL = gsap.timeline({
      scrollTrigger: {
        trigger: item,
        start: itemStart,
        end: itemEnd,
        toggleActions: itemToggleActions
      }
    });

    const animation = gsap.effects[animationType](item, { duration, stagger });
    independentTL.add(animation, delay || 0);
  }

  addToTimeline(item, index, timeline, isStaggerGroup) {
    const animationType = item.getAttribute('data-scroll-animation') || 'fadeUp';
    const duration = parseFloat(item.getAttribute('data-scroll-duration')) || 0.5;
    const stagger = parseFloat(item.getAttribute('data-scroll-stagger')) || 0.1;
    const delay = parseFloat(item.getAttribute('data-scroll-delay')) || 0;
    const positionAttr = item.getAttribute('data-scroll-position');

    if (!gsap.effects[animationType]) {
      console.warn(`Animation effect "${animationType}" not found`);
      return;
    }

    const timelinePosition = positionAttr !== null ? positionAttr :
      (index === 0 ? 0 : this.GLOBAL_OVERLAP);

    let animation;

    if (isStaggerGroup) {
      animation = this.createStaggerGroupAnimation(item, animationType, duration, stagger);
    } else {
      animation = gsap.effects[animationType](item, { duration, stagger });
    }

    timeline.add(animation, delay ? `+=${delay}` : timelinePosition);
  }

  createStaggerGroupAnimation(item, animationType, duration, stagger) {
    const groupItems = Array.from(item.children);
    const animation = gsap.timeline();

    animation.add(gsap.effects[animationType](groupItems, { duration, stagger }));

    groupItems.forEach(groupItem => {
      if (groupItem.hasAttribute('data-scroll-stagger-children')) {
        const childElements = Array.from(groupItem.children);
        animation.add(
          gsap.effects[animationType](childElements, { duration, stagger }),
          0
        );
      }
    });

    return animation;
  }
}

// Usage
document.addEventListener('DOMContentLoaded', () => {
  gsap.registerPlugin(ScrollTrigger, SplitText);

  // Initialize effects system
  new GsapEffects();

  // Initialize scroll animations
  new ScrollTriggerAnimator().init();
});
