(function ($, Drupal, once, drupalSettings) {
  Drupal.behaviors.orange_yelen_trombino = {
    attach: function (context, settings) {
      if (context !== document) {
        return;
      }
      $('select#edit-type--2', context).on('change', function (){
        $(this).closest('form').submit();
      })

      /*once('orange_yelen_trombino', 'html').forEach((element) => {
        console.log('--- orange_yelen_trombino user_email ---', drupalSettings.orange_yelen_trombino.user_email)
        console.log('--- Oorange_yelen_trombino username ---', drupalSettings.orange_yelen_trombino.username)

      })*/
    }
  };
})(jQuery, Drupal, drupalSettings);
