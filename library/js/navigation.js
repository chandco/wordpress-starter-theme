




$menu = $("#menu-primary-navigation-1").clone();
$menu.attr("id","popup-menu").appendTo("body");






$("#navigation-dropdown").click(function(e) {
        e.preventDefault();
        $("body").toggleClass("navigation-menu-open");
        $("#navigation-dropdown i").toggleClass('fa-bars').toggleClass('fa-caret-up');
       
    });
