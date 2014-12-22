





// navigation stuff
require(['../navigation']);


require(['magnific-popup'], function() {
	$('.popup-button').magnificPopup({
	 
	  items: {
	  	src: $('.popup-button').siblings('.popup'),
	  	type:'inline',
	  },
	  midClick: true // Allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source in href.
	});
});


// Conditionally load stuff:

// For example forms, gallery popups, ligthboxes and carousels, don't load their stuff until 


// Cycle needs to see jQuery as well as $


require(['slick'], function() {
  
  $('.cycle-slideshow').slick({
  		slide: 'div',
  		//autoplay: true,
  	//	prevArrow: '.cycle-prev',
  	//	nextArrow: '.cycle-next',
  		lazyLoad: 'ondemand',
  		dots: true,
  });  
});




// Magnific popup should be external

require(['jquery-exists'], function() {


	$("nav.pagination").exists(function() {

		var data = {};



		data.nextpage = $("nav.pagination a.next.page-numbers").attr("href");
		
		var nav = $(this);
		require(['isonscreen'], function() {
			// nav is 
			console.log(nav);
			$("nav.pagination").hide();

			$("<div id='archive-nav-placeholder'></div>").appendTo("#main");
		 	
		    
		 	function archiveLazyload(data) {
		        
		 		if ($("#archive-nav-placeholder").isOnScreen())
		 		{	

		 			var $holdingbay;

		 			
		 			$("#archive-nav-placeholder").html('<i class="fa fa-4x fa-refresh fa-spin"></i>');

		 			$("nav.pagination").remove();
		            
		            $(window).off('scroll',checkForLoad);

		            $.get(data.nextpage, function(nextpagedata){

		                $holdingbay = $("<div>");
		                $holdingbay.html(nextpagedata);

		                window.loading = true;
		                
						//$("#archive-nav-placeholder").hide();
		                $newarticles = $holdingbay.find("#main article.post");
		                
		                $newarticles.insertBefore("#archive-nav-placeholder");
		            
						
		 				   
			 			$("#archive-nav-placeholder").html("");

			                
			            if ($holdingbay.find("nav.pagination a.next.page-numbers").length) {
			                    // more pages
			                    
			                    data.nextpage = $holdingbay.find("nav.pagination a.next.page-numbers").attr("href");
			                    
			                    $holdingbay.remove();
			                    $(window).on('scroll', data, checkForLoad );

			               
		 				}
		 			});


		 		} 
		 	}
		 	var settings = {
	            callBackTime: 100,
	            timer: 0,
	        };

		    var checkForLoad = function(e) {
	            if (settings.timer ||  window.loading) {
	                clearTimeout(settings.timer);
	            }

	            // Use a buffer so we don't call trackLocation too often.
	            settings.timer = setTimeout(function() {
	                archiveLazyload(e.data);
	                
	            }, settings.callBackTime);
	        };

		    if ($("nav.pagination").length) {
		        $(window).on('scroll',data, checkForLoad);
		    }

		});
	});



	$("div.gallery").exists( function() {

		var popup = {
			    gallery : {
			                type:'image', 
			                delegate: 'a', 
			                gallery:
			                    {
			                        enabled:true
			                    },
			                removalDelay: 300,
			                mainClass: 'mfp-fade',
			                zoom: {
								enabled: true
							}

			            },

			    featured : {
			      type:'image',
			      gallery: { enabled:false },
			      removalDelay: 300,
			      mainClass: 'mfp-fade',
			      verticalFit: true,
			      image: {
			        markup: '<div class="mfp-figure">'+
			            '<div class="mfp-close"></div>'+
			            
			            '<div class="mfp-img"></div>'+
			            
			            
			          '</div>',
			      },
			      zoom: {
			            enabled: true, // By default it's false, so don't forget to enable it

			            duration: 300, // duration of the effect, in milliseconds
			            easing: 'ease-in-out', // CSS transition easing function 

			            // The "opener" function should return the element from which popup will be zoomed in
			            // and to which popup will be scaled down
			            // By defailt it looks for an image tag:
			            opener: function(openerElement) {
			              // openerElement is the element on which popup was initialized, in this case its <a> tag
			              // you don't need to add "opener" option if this code matches your needs, it's defailt one.
			              return openerElement.is('img') ? openerElement : openerElement.find('img');
			            }
			          },
			        closeOnContentClick: true,
			    },
			};

		var $gallery = $(this);
		require(['magnific-popup'], function(  ) {

			console.log($gallery);
			$gallery.magnificPopup(popup.gallery);	
		});
	});


});