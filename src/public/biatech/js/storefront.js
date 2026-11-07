/* Optional progressive enhancement; checkout and catalog work without this file. */
(() => {
  'use strict';
  const nodes = document.querySelectorAll('.reveal-on-scroll');
  if (!('IntersectionObserver' in window) || window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    nodes.forEach((node) => node.classList.add('is-visible'));
    return;
  }
  const observer = new IntersectionObserver((entries) => {
    for (const entry of entries) {
      if (!entry.isIntersecting) continue;
      entry.target.classList.add('is-visible');
      observer.unobserve(entry.target);
    }
  }, { threshold: 0.08, rootMargin: '0px 0px -28px 0px' });
  nodes.forEach((node) => observer.observe(node));
})();
