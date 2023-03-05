/**
 * @file
 * Theme hooks for the Drupal Bootstrap base theme.
 */
(function ($, Drupal, Bootstrap) {
	console.log("LOADED");
	$('.region-nav-main').on('click', '.search-toggle', function(e) {
		var selector = $(this).data('selector');
	  console.log("selector: ", selector);
		$(selector).toggleClass('show').find('.search-input').focus();
		$(this).toggleClass('active');
	  
		e.preventDefault();
	  });


	/* Smooth scroll */
	$('a.page-scroll').bind('click', function(event) {
        var $anchor = $(this);
        $('html, body').stop().animate({
            scrollTop: $($anchor.attr('href')).offset().top
        }, 1500, 'easeInOutExpo');
        event.preventDefault();
    });
	
	/* Hide all months */
	/*$(".views-row").addClass("hidden");*/
	
	/* Find current Month */
	var m_names = new Array("January", "February", "March", 
	"April", "May", "June", "July", "August", "September", 
	"October", "November", "December");

	var d = new Date();
	var curr_date = d.getDate();
	var curr_month = d.getMonth();
	var curr_year = d.getFullYear();
	var month = (m_names[curr_month]);
	
	/* Show current Month */
	$("."+month+".views-row").removeClass("hidden");
	
	

})(window.jQuery, window.Drupal, window.Drupal.bootstrap);
