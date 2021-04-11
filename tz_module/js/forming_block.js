/**
 * Block render
 */

(function ($, Drupal, drupalSettings) {
  $(document).ready(function () {
    $.ajax({
      type: 'POST',
      cache: false,
      url: drupalSettings.statistic_block_info.url,
      data: drupalSettings.statistic_block_info.data,
      success: function(result) {
        $('.statistics_information_block').append(result);
      }
    });
  });
})(jQuery, Drupal, drupalSettings);
