@extends('auth.base')

@section('css')
    <link href="{{ URL::asset('/') }}auth/css/plugins/blueimp/css/blueimp-gallery.min.css" rel="stylesheet">
@endsection

@section('contents')
    <div class="wrapper wrapper-content animated fadeIn">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h2>我的相册</h2>
                    </div>
                    <div class="ibox-content">
                        @if (count($photos) > 0)
                            <div class="lightBoxGallery">
                                @foreach ($photos as $value)
                                    <a href="{{ config('config.qiniu.url') . $value->photo_key }}" title="{{ $value->photo_name }}" data-gallery=""><img src="{{ config('config.qiniu.url') . $value->photo_key }}?imageView2/1/w/140"></a>
                                @endforeach

                                <div id="blueimp-gallery" class="blueimp-gallery">
                                    <div class="slides"></div>
                                    <h3 class="title"></h3>
                                    <a class="prev">‹</a>
                                    <a class="next">›</a>
                                    <a class="close">×</a>
                                    <a class="play-pause"></a>
                                    <ol class="indicator"></ol>
                                </div>

                            </div>
                            <div class="clearfix">
                                <span class="pull-right">{!! $photos->render() !!}</span>
                            </div>
                        @else
                            <p class="text-center m-t">还未上传图片<a href="{{ URL::route('authMediaAdd') }}">去上传图片</a></p>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ URL::asset('/') }}auth/js/plugins/blueimp/jquery.blueimp-gallery.min.js"></script>
@endsection