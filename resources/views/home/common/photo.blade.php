@inject('widget', 'App\Services\Widget')
<div class="sidebar hidden-xs">
    <div class="sidebar-heading">
        <h4 class="sidebar-title">
            <i class="glyphicon glyphicon-picture" aria-hidden="true"></i>
            最新图片
        </h4>
    </div>
    <div class="sidebar-body">
        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
            <!-- Wrapper for slides -->
            <div class="carousel-inner" role="listbox">
                @foreach($widget->getPhoto() as $key => $value)
                    <a class="polaroid" title="{{ $value->photo_name }}" data-gallery="" href="{{ config('config.qiniu.url') . $value->photo_key }}">
                        <img src="{{ config('config.qiniu.url') . $value->photo_key }}?imageView2/1/w/400" alt="{{ $value->photo_name }}">{{ $value->photo_name }}
                    </a>
                    {{--<div class="item @if ($key == 0) active @endif">--}}
                        {{--<img src="{{ config('config.qiniu.url') . $value->photo_key }}?imageView2/1/w/400" alt="{{ $value->photo_name }}">--}}
                    {{--</div>--}}
                @endforeach
            </div>
        </div>
    </div>
</div>