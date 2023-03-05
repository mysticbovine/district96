/**
 * @file
 * JS code for setting print view of page.
 */

(function () {
  'use strict';

  /**
   * Set the print view of the page.
   */
  Drupal.behaviors.printLink = {
    attach: function (context) {
      const printLink = context.querySelector('#printlink');
      printLink.addEventListener('click', function (e) {
        const links = document.getElementsByTagName('link');
        Array.from(links).forEach(function (link) {
          const rel = printLink.getAttribute('rel');
          link.setAttribute('href', rel);
        });

        e.preventDefault();
      });
    }
  };

})(Drupal);
