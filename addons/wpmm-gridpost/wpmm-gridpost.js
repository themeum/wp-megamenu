jQuery(document).ready(function($){'use strict';


	$(document).on( 'click', '.wpmm-right,.wpmm-left', function( event ) {
		var $that = $(this);
		if( $that.hasClass('disablebtn') ){ return; }
		
		var oderby 		= $that.data('oderby'),
            column 		= $that.data('column'),
            showcat 	= $that.data('showcat'),
            type 		= $that.data('type'),
            current 	= $that.attr('data-current'),
            category	= $that.data('category'),
            total 		= $that.data('total'),
            count 		= $that.data('count');


        if( $that.hasClass('wpmm-right') ){
        	current = parseInt(current) + 1;
        	if( current == total ){
        		$that.parent().find('.wpmm-left').removeClass('disablebtn');
	        	$that.addClass('disablebtn');
	        }else{
	        	if( current == 2 ){
	        		$that.parent().find('.wpmm-left').removeClass('disablebtn');
	        	}
	        }
        } else {
        	current = current - 1;
        	if( current == 1 ){
        		$that.parent().find('.wpmm-right').removeClass('disablebtn');
	        	$that.addClass('disablebtn');
	        }else{
	        	$that.parent().find('.wpmm-right').removeClass('disablebtn');
	        }
        }


        //console.log( current );
        $that.parent().find('.wpmm-right').attr( 'data-current', current );
        $that.parent().find('.wpmm-left').attr( 'data-current', current );

		$.ajax({
			url: wpmm_object.ajax_url,
			type: 'POST',
			data: { action: 'gridpost_load_more_posts', oderby: oderby, column: column, current: current, category: category, type: type, showcat: showcat, count: count },
			beforeSend: function(){
				$that.parent().find( ".wpmm-grid-post-addons" ).addClass('spinwarp').append( '<div class="spinners"></div>' );
			}
		})
		.done(function(data) {
			$that.parent().find('.wpmm-grid-post-addons').removeClass('spinwarp').html( data );
		})
		.fail(function() {
			console.log( "Failed" );
		});
	});


	// Tab JS
	$('.wpmm-vertical-tabs').each(function(){
		var $tab = $(this),
		$handlers = $tab.find('.wpmm-tab-btns').children(),
		$tabs = $tab.find('.wpmm-tab-content').children();

		$handlers.each(function(index) {
			$(this).on('mouseenter', function(event){
				event.preventDefault();
				$handlers.removeClass('active');
				$tabs.removeClass('active');
				$(this).addClass('active');
				$($tabs[index]).addClass('active');
			});
			// $(this).on('click', function(event){ event.preventDefault(); });
		});
	});


});	