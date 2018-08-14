(function ($, Drupal) {
  Drupal.behaviors.slick = {
    attach: function (context, settings) {
      console.log("foo");
      $('.slider-for .slick-slider:not(.processed)').addClass('processed').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        fade: true,
        asNavFor: '.slider-nav .slick-slider',
        arrows: false,
      });
      $('.slider-nav .slick-slider:not(.processed)').addClass('processed').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        asNavFor: '.slider-for .slick-slider',
        dots: true,
        centerMode: false,
        focusOnSelect: true,
        appendDots: '.slider-divider',
        appendArrows: '.slider-divider',
        autoplay: true,
        autoplaySpeed: 5000,
        responsive: [
          {
            breakpoint: 1080,
            settings: {
              slidesToShow: 4,
            }
          },
          {
            breakpoint: 720,
            settings: {
              slidesToShow: 3,
            }
          },
          {
            breakpoint: 360,
            settings: {
              slidesToShow: 2,
            }
          }
        ]
      });

    }
  };
})(jQuery, Drupal);
