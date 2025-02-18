tns({
    container: '.category-slider',
    items: 3,
    slideBy: 'page',
    autoplay: false,
    mouseDrag: true,
    gutter: 0,
    nav: false,
    controls: true,
    controlsText: ['<i class="lni lni-chevron-left"></i>', '<i class="lni lni-chevron-right"></i>'],
    responsive: {
        0: {
            items: 1,
        },
        540: {
            items: 2,
        },
        768: {
            items: 4,
        },
        992: {
            items: 5,
        },
        1170: {
            items: 6,
        }
    }
});