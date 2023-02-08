jQuery(document).ready(function(){

    // Select and loop the container element of the elements you want to equalise
    jQuery('#freelancers-widget').each(function(){

      // Cache the highest
      var highestBox = 0;

      // Select and loop the elements you want to equalise
      jQuery('.card-of-user', this).each(function(){

        // If this box is higher than the cached highest then store it
        if(jQuery(this).height() > highestBox) {
          highestBox = jQuery(this).height();
        }

      });

      // Set the height of all those children to whichever was highest
      jQuery('.card-of-user',this).height(highestBox);

    });


    jQuery('#categories-widget').each(function(){

      // Cache the highest
      var highestBox = 0;

      // Select and loop the elements you want to equalise
      jQuery('.my_category_image_holder', this).each(function(){

        // If this box is higher than the cached highest then store it
        if(jQuery(this).height() > highestBox) {
          highestBox = jQuery(this).height();
        }

      });

      // Set the height of all those children to whichever was highest
      jQuery('.my_category_image_holder',this).height(highestBox);

    });



});

jQuery(function() {
    "use strict";
    feather.replace(), jQuery(".preloader").fadeOut(), jQuery(".nav-toggler").on("click", function() {
        jQuery("#main-wrapper").toggleClass("show-sidebar"), jQuery(".nav-toggler i").toggleClass("ti-menu")
    }), jQuery(function() {
        jQuery(".service-panel-toggle").on("click", function() {
            jQuery(".customizer").toggleClass("show-service-panel")
        }), jQuery(".page-wrapper").on("click", function() {
            jQuery(".customizer").removeClass("show-service-panel")
        })
    }), jQuery(function() {
        jQuery('[data-toggle="tooltip"]').tooltip()
    }), jQuery(function() {
        jQuery('[data-toggle="popover"]').popover()
    }), jQuery(".message-center, .customizer-body, .scrollable, .scroll-sidebar").perfectScrollbar({
        wheelPropagation: !0
    }), jQuery("body, .page-wrapper").trigger("resize"), jQuery(".page-wrapper").delay(20).show(), jQuery(".list-task li label").click(function() {
        jQuery(this).toggleClass("task-done")
    }), jQuery(".show-left-part").on("click", function() {
        jQuery(".left-part").toggleClass("show-panel"), jQuery(".show-left-part").toggleClass("ti-menu")
    }), jQuery(".custom-file-input").on("change", function() {
        var e = jQuery(this).val();
        jQuery(this).next(".custom-file-label").html(e)
    })
});
