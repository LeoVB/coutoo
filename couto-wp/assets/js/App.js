(function($) {
  $( document ).ready(function() {
    $('.owl-carousel').owlCarousel({
      loop:true,
      margin:20,
      nav:false,
      responsive:{
          0:{
              items:1 , 
              
          },
          600:{
              items:3
          },
          1000:{
              items:2
          }
      }
  })
});
})(jQuery);