$(document).ready(function(){
  $('.slicker').slick({
    slidesToShow: 3,
    slidesToScroll: 1,
    autoplay: true,
    autoplaySpeed: 2000,
  });

  $("#faq-block").find(".sub-category-link").on('change',function () {
    if ($(this).is(":checked")) {
        var url = $(this).closest(".form-check").find("a").attr("href");
            window.location.href = url;     
    }
});

});


