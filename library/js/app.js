









// Conditionally load stuff:

// For example forms, gallery popups, ligthboxes and carousels, don't load their stuff until 


// Cycle needs to see jQuery as well as $


require(['slick'], function() {
  
  $('.cycle-slideshow').slick({
  		slide: 'div',
  		//autoplay: true,
  		prevArrow: '.cycle-prev',
  		nextArrow: '.cycle-next',
  		lazyLoad: 'ondemand',

  });

 





  
});


// Magnific popup should be external

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
		                mainClass: 'mfp-fade'  

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
		}

	var $gallery = $(this);
	require(['magnific-popup'], function(  ) {

		console.log($gallery);
		$gallery.magnificPopup(popup.gallery);	
	});
});