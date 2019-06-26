+function($) {
    $.fn.showPhotos = function(configs) {
        var settings = $.extend({
            rotate: 20,
            time: 6000,
            header: 0,
            zindex: 1000,
            moveSpeed: 600,
        },
        configs || {});
        var $slef = $(this);
        $slef.imagesLoaded(function() {
            changePhotos();
            var photosTime = window.setInterval(function() {
                changePhotos();
            },
            settings.time)
        });
        function changePhotos() {
            var photoBoxHeight = $slef.height();
            var photoBoxWidth = $slef.width();
            $slef.find(".polaroid").each(function() {
                var rotate = "rotate(" + random( - settings.rotate, settings.rotate) + "deg)";
                var index = random(settings.zindex);
                var css = {
                    "-webkit-transform": rotate,
                    "-ms-transform": rotate,
                    "transform": rotate,
                    "z-index": index
                };
                $(this).css(css).data('css', css); 
                var photoWidth = $(this).outerWidth();
                var photoHeight = $(this).outerHeight();
                var ratio = photoWidth / photoHeight;
                var photoMaxWidth = photoBoxWidth > 800 ? 250 : Math.floor(photoBoxHeight / 3);
                var photoMinWidth = photoBoxWidth > 800 ? 150 : 50;
                var sizeRandom = random(photoMinWidth, photoMaxWidth);
//                var widthRatio = Math.floor(sizeRandom / photoMaxWidth * 100);

                $(this).css("width", sizeRandom + "px");
                $(this).css("height", "auto");

                photoWidth = $(this).outerWidth();
                photoHeight = $(this).outerHeight();
                var heightDiff = Math.floor(photoBoxHeight - photoHeight);
                var widthDiff = Math.floor(photoBoxWidth - photoWidth);

                var maxTop = Math.floor(heightDiff / photoBoxHeight * 100);
                var maxLeft = Math.floor(widthDiff / photoBoxWidth * 100);
                var top = random(maxTop);
                var left = random(maxLeft);
                var rotate = "rotate(" + random( - settings.rotate, settings.rotate) + "deg)";
                $(this).animate({
                    "top": top + "%",
                    "left": left + "%"
                },
                settings.moveSpeed)
            })
            $slef.css({opacity: 1});
        }
        function random(max, min) {
            if (!min) {
                min = 0
            }
            var range = max - min;
            return (min + Math.floor(Math.random() * range))
        }
    }
} (jQuery);+
function($) {
    $.fn.showHeader = function(configs) {
        var settings = $.extend({
            newClass: "bg",
        },
        configs || {});
        var $this = $(this);
        $(window).scroll(function() {
            var winTop = $(this).scrollTop();
            if (winTop > 0) {
                $("#top").addClass(settings.newClass)
            } else {
                $("#top").removeClass(settings.newClass)
            }
        })
    }
} (jQuery);+
function($) {
    $.fn.backToTop = function(configs) {
        var settings = $.extend({
            time: 300,
            header: 0,
        },
        configs || {});
        var $this = $(this);
        $(window).scroll(function() {
            if ($(this).scrollTop() > settings.header) {
                $this.fadeIn()
            } else {
                $this.fadeOut()
            }
        });
        $this.on("click",
        function(e) {
            e.preventDefault();
            $("html, body").animate({
                scrollTop: 0
            },
            settings.time)
        })
    }
} (jQuery);