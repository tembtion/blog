@extends('home.common.base')

@section('title', "")

@section('css')
<link rel="stylesheet" href="{{ URL::asset('/') }}home/css/album.css" type="text/css" media="all">
@endsection

@section('main')
    <div id="photo"></div>
    <div id="more"></div>
@endsection

@section('footer')
@endsection

@section('js')
    <script type="text/javascript" src="{{ URL::asset('/') }}home/js/imagesloaded.js"></script>
    <script type="text/javascript" src="{{ URL::asset('/') }}home/js/masonry.js"></script>
    <script type="text/javascript" src="{{ URL::asset('/') }}home/js/photo.js"></script>
    <script type="text/javascript">
        <!--
        $(function(){
            var photo = new Photo({
                'url': "{{ URL::route('homeAlbumItem') }}",
                'container': $('#photo'),
                'columnWidth': 236,
                'gutter': 16,
                'itemSelector': '.photo-item'});
        })
        //-->
    </script>
@endsection