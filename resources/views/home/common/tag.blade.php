@inject('widget', 'App\Services\Widget')
<div class="sidebar hidden-xs">
    <div class="title">
        <h2>
            <i aria-hidden="true" class="glyphicon glyphicon-tags"></i>
            标签云
        </h2>
    </div>
    <div id="wpcumuluswidgetcontent9218562" class="sidebar-body tag">
        <tags>
            @foreach($widget->getTag() as $value)
                <a style='font-size: 12px;'
                   href="{{ URL::route('homeTagIndex', ['term_id' => $value->term_id]) }}">{{ $value->term->name }}</a>
            @endforeach
        </tags>
    </div>
</div>
<script type="text/javascript" src="{{ URL::asset('/') }}home/plugins/tagcloud/swfobject.js"></script>
<script type="text/javascript">
    $(function () {
        var swf2 = navigator.plugins['Shockwave Flash'];
        if (swf2 != undefined) {
            var tagcloud = document.getElementById("wpcumuluswidgetcontent9218562").innerHTML;
            var widget_so1179849 = new SWFObject("{{ URL::asset('/') }}home/plugins/tagcloud/tagcloud.swf", "tagcloudflash", "260", "260", "9", "#ffffff");
            widget_so1179849.addParam("wmode", "transparent");
            widget_so1179849.addParam("allowScriptAccess", "always");
            widget_so1179849.addVariable("tcolor", "0x333333");
            widget_so1179849.addVariable("tcolor2", "0x333333");
            widget_so1179849.addVariable("hicolor", "0x000000");
            widget_so1179849.addVariable("tspeed", "100");
            widget_so1179849.addVariable("distr", "true");
            widget_so1179849.addVariable("mode", "both");
            widget_so1179849.addVariable("tagcloud", encodeURI(tagcloud));
            widget_so1179849.write("wpcumuluswidgetcontent9218562");
        }
        $('#wpcumuluswidgetcontent9218562').slideDown("slow");
    })


</script>