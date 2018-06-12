jQuery(document).ready(function($){'use strict';

	var clr = '';
	var clr_bg = '';
	$(".wpmm-featurebox-hcolor").on({
	    mouseenter: function () {
	     	clr = $(this).css('color');
			clr_bg = $(this).css('backgroundColor');
			$(this).css("color", $(this).data("hover-color"));
			$(this).css("background-color", $(this).data("hover-bg-color"));
	    },
	    mouseleave: function () {
	        $(this).css("color", clr );
			$(this).css("background-color", clr_bg );
	    }
	});

});	