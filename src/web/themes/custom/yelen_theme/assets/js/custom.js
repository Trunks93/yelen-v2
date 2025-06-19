(function ($, Drupal, drupalSettings) {
  console.log('---CUSTOM JS----')
  console.log('---CUSTOM JS - Document Title----', document.title)
  let checkLoginExecuted = false;
  const isPageReloaded = sessionStorage.getItem('isAccessDeniedPageReloaded')
  console.log('---isPageReloaded----', isPageReloaded)
  Drupal.behaviors.checkLogin = {
    attach: function (context, settings) {
      const accessDeniedPageClass = document.body.querySelector('.body.access-denied-page')
      const isAccessDeniedPage = accessDeniedPageClass && (document.title.includes('Access denied') || document.title.includes('Accès refusé'))
      console.log('---isAccessDeniedPage---', isAccessDeniedPage)
      console.log('---accessDeniedPageClass---', accessDeniedPageClass)
      console.log('---drupalSettings.user.isLoggedIn---', drupalSettings.user.isLoggedIn)
      if(!isAccessDeniedPage) return;
      if(checkLoginExecuted || isPageReloaded || !drupalSettings.user.isLoggedIn) return;
      checkLoginExecuted = true
      setTimeout(() => {
        location.reload();
        sessionStorage.setItem('isAccessDeniedPageReloaded', true)
      }, 500);
      setTimeout(() => {
        sessionStorage.removeItem('isAccessDeniedPageReloaded')
      }, 600);
    }
  };
})(jQuery, Drupal, drupalSettings);
