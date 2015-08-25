$(function() {
    //bootstrap tooltips.
    $('[data-toggle="tooltip"]').tooltip(); 
    //Scrolling  - Requires jQuery Easing plugin
    $('a.page-scroll').bind('click', function(event) {
        event.preventDefault();

        var anchor = $(this);
        if(anchor.attr('href') == "#page-top"){
            var offset = 100;
        }
        // else if(anchor.attr('href') == "#about"){
        //    var offset = 80;
        // }
        else{
            if($(window).width() < 768){
                var offset = 45;
            }
            else{      
                var offset = 80;
            }
        }
        $('html, body').stop().animate({
            scrollTop: $(anchor.attr('href')).offset().top - offset
        }, 1500, 'easeInOutExpo');
    });
    // Closes the Responsive Menu on Menu Item Click
    $('.navbar-collapse ul li a').click(function() {
        $('.navbar-toggle:visible').click();
    });
});