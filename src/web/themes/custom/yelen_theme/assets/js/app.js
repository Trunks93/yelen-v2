let swiper = new Swiper(".mySwiper", {
  slidesPerView: 1,
  spaceBetween: 30,
  loop: true,
  pagination: {
    el: ".swiper-pagination",
    clickable: true,
  },
  navigation: {
    nextEl: ".swiper-button-next",
    prevEl: ".swiper-button-prev",
  },
});

console.log('---APP JS----')
console.log('---APP JS - Document Title----', document.title)
if (document.body.classList.contains('user-logged-in') && (document.title.includes('Access denied') || document.title.includes('Accès refusé'))) {
  setTimeout(() => {
    console.log('---403 PAGE RELOAD AFTER 500MS----')
    location.reload();
  }, 500);
}
