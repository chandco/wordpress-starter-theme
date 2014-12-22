
require.config({

  baseUrl : rjs_baseURL,
	paths: {
        // the left side is the module ID,
        // the right side is the path to
        // the jQuery file, relative to baseUrl.
        // Also, the path should NOT include
        // the '.js' file extension. This example
        // is using jQuery 1.9.0 located at
        // js/lib/jquery-1.9.0.js, relative to
        // the HTML page.
        //jquery  : "//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min",
        app     : "../app",
        bones   : "../bones"
    },
    "shim": {
        'slick': {
            deps: ['jquery']
        }
      }
});



/* 
  ALWAYS ON STUFF 
  Basically anything that we would want on every page, not conditional on what's there, eg responsive stuff.
*/


require(["modernizr"]);

// Setup responsive images:
document.createElement( "picture" );
require(['picturefill']);

// Anything we build, build it in app, but use app to conditionally load more modules.
require(['jquery'], function(jQuery) {
  $ = jQuery; // compatability
  require(['app']); // now everything has access to jquery and any plugins we might add above
});

// bones
require(['bones']);

/*
  require(['skrollr'], function(skrollr){
      var s = skrollr.init();
  });

*/


