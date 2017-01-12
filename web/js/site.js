$(document).ready(function(){
    $(".owl-carousel").owlCarousel({
        loop: true,
        nav: true,
        dots: false,
        navText: [
            "<i class='fa fa-chevron-right'></i>",
            "<i class='fa fa-chevron-left'></i>"
        ],
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 3
            },
            1000: {
                items: 5
            }
        }
    });
});