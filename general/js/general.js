(function ($, Drupal, drupalSettings) {
  'use strict';
  Drupal.behaviors.general = {
    attach: function (context) {
        if (drupalSettings.general) {
           $('.user_role').html(drupalSettings.general.role).once('general');
        }
    }
  };
})(jQuery, Drupal, drupalSettings);