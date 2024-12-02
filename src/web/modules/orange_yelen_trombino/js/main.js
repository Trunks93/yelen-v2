(function ($, Drupal, once, drupalSettings) {
  Drupal.behaviors.orange_yelen_trombino = {
    attach: function (context, settings) {
      if (context !== document) {
        return;
      }
      $('select#edit-type--2', context).on('change', function (){
        $(this).closest('form').submit();
      })
    }
  };
})(jQuery, Drupal, once, drupalSettings);
