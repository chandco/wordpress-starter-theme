require.config({baseUrl:rjs_baseURL,paths:{app:"../app",bones:"../bones"},shim:{slick:{deps:["jquery"]}}}),require(["modernizr"]),document.createElement("picture"),require(["picturefill"]),require(["jquery","jquery-exists"],function(e){$=e,require(["app"])}),require(["bones"]);