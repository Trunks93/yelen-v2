$(document).ready(function(){
  $('.slicker').slick({
    slidesToShow: 3,
    slidesToScroll: 1,
    autoplay: true,
    autoplaySpeed: 2000,
  });

  $('#faq-block').find(sub-category-link).on('check', function(e){
    console.log('Test dsj')
    $('.sub-category-link').find(orange-color-link).on('click', function(e){
        console.log('Test')
    })
  })

});


