
  console.log('---CUSTOM JS----')
  console.log('---CUSTOM JS - Document Title----', document.title)
  let checkLoginExecuted = false;
  const isPageReloaded = sessionStorage.getItem('isAccessDeniedPageReloaded')
  console.log('---isPageReloaded----', isPageReloaded)
  const accessDeniedPageClass = document.body.querySelector('.body.access-denied-page')
  const isAccessDeniedPage = accessDeniedPageClass && (document.title.includes('Access denied') || document.title.includes('Accès refusé'))
  if(isAccessDeniedPage && !isPageReloaded){
    document.title = "Chargement..."
    document.body.querySelector('.unauthorized').innerHTML = '<div class="spinner-border" role="status"><span class="visually-hidden">Chargement...</span></div>'
    setTimeout(() => {
      console.log('----Page Reloaded after 100ms-----')
      sessionStorage.setItem('isAccessDeniedPageReloaded', 'true')
      location.reload();
    }, 100);
  }
  setTimeout(() => {
    sessionStorage.removeItem('isAccessDeniedPageReloaded')
  }, 1000);
