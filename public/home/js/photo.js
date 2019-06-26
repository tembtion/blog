var Photo = function(options){
    var self = this;
    self.$container = options.container;
    self.columnWidth = options.columnWidth;
    self.gutter = options.gutter;
    self.itemSelector = options.itemSelector;
    self.url = options.url;
    self.loading = false;
    self.page = 1;
    self.close = false;

    self.setWidth();
    self.appendContent();

    $(window).resize(function() {
        self.setWidth();
    });

    $(window).scroll(function() {
        var totalheight = parseFloat($(window).height()) + parseFloat($(window).scrollTop());     //浏览器的高度加上滚动条的高度 

        if (!self.close && $(document).height() <= totalheight + 220) { 
            //加载数据
            self.appendContent();
        }
    });
}

Photo.prototype.appendContent = function() {
    var self = this;
    if (self.loading) {
        return false;
    }
    self.loading = true;
    $('#more').addClass('show');
    $.ajax({
        url : self.url,
        data : {'page':self.page},
        dataType : 'json', 
        success:function(response){
            if (response.result !== true) {
                alert('error');
                return false;
            }
            if (response.content == '') {
                self.close = true;
                $('#more').addClass('end');
                return false;
            }
            var $content = $(response.content);
            $content.imagesLoaded(function(){
                self.$container.append($content).masonry( 'appended', $content );
                self.setWidth();
            });
            self.page++;
        },
        complete:function(){
            self.loading = false;
            $('#more').removeClass('show');
        },
        error:function(){
            layer.msg('请求失败请重新提交', {icon: 5});
        }
    })
}

Photo.prototype.setWidth = function() {
    var self = this;
    var windowWidth = $(window).width() > self.maxWidth ? self.maxWidth : $(window).width();
    var size = Math.floor((windowWidth + self.gutter) / (self.columnWidth + self.gutter));

    if (size < 2) {
        var gutter = 10;
        var columnWidth = Math.floor((windowWidth - 3 * gutter) / 2);
        var containerWidth = windowWidth - 2 * gutter;
    } else {
        var gutter = self.gutter;
        var columnWidth = self.columnWidth;
        var containerWidth = (size - 1) * self.gutter + size * columnWidth;
    }

    self.$container.find(self.itemSelector).css({
        'width' : columnWidth,
        'margin-bottom' : gutter,
    });
    self.$container.css('width', containerWidth).masonry({
        columnWidth: columnWidth,
        gutter: gutter,
        itemSelector : self.itemSelector
    });
}