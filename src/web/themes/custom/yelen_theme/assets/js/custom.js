const isPageReloaded = sessionStorage.getItem('isAccessDeniedPageReloaded')
const accessDeniedPageClass = document.body.querySelector('.body.access-denied-page')
const isAccessDeniedPage = accessDeniedPageClass && (document.title.includes('Access denied') || document.title.includes('Accès refusé'))
if(isAccessDeniedPage && !isPageReloaded){
  document.title = "Chargement..."
  document.body.querySelector('.unauthorized').innerHTML = '<div class="spinner-border" role="status"><span class="visually-hidden">Chargement...</span></div>'
  setTimeout(() => {
    sessionStorage.setItem('isAccessDeniedPageReloaded', 'true')
    location.reload();
  }, 100);
}
setTimeout(() => {
  sessionStorage.removeItem('isAccessDeniedPageReloaded')
}, 1000);
